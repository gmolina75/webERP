<?php
/**
 * webERP Security Scanner
 * -----------------------
 * Static analysis scan for common vulnerabilities:
 *  - SQL Injection (string concatenation in queries)
 *  - XSS (unescaped output)
 *  - Code injection (eval/exec/shell_exec)
 *  - Insecure deserialization (unserialize)
 *  - Unsafe file operations (include/require with variables)
 *  - MD5/SHA1 usage (weak hashes)
 *  - Direct access to files with variable paths
 *
 * Usage: php bin/security-scan.php [--fix-check]
 * Exit code 0 = clean, 1 = findings, 2 = fatal error
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

$ROOT = dirname(__DIR__);
$EXCLUDE_DIRS = ['.git', 'vendor', 'companies'];

$rules = [
    'sql_injection_concat' => [
        'pattern' => '/DB_query\s*\(\s*(?:["\x27].*?["\x27]|["\x27])\s*\.\s*|\$SQL\s*=\s*["\x27][^"\x27]*["\x27]\s*\.\s*\$/',
        'severity' => 'HIGH',
        'message' => 'Possible SQL injection via string concatenation',
    ],
    'xss_unescaped_echo' => [
        'pattern' => '/echo\s+["\x27]?\s*\$_(POST|GET|REQUEST|COOKIE|SERVER)\[/',
        'severity' => 'HIGH',
        'message' => 'Possible XSS: superglobal echoed without escaping',
    ],
    'code_injection' => [
        'pattern' => '/\b(eval|exec|shell_exec|passthru|proc_open|system|popen)\s*\(\s*\$/',
        'severity' => 'CRITICAL',
        'message' => 'Possible code/command injection with variable argument',
    ],
    'insecure_deserialization' => [
        'pattern' => '/\bunserialize\s*\(\s*\$_/',
        'severity' => 'CRITICAL',
        'message' => 'Insecure deserialization of user input (RCE risk)',
    ],
    'dynamic_include' => [
        'pattern' => '/(include|require|include_once|require_once)(_file)?\s*\(\s*\$/',
        'severity' => 'HIGH',
        'message' => 'Dynamic include/require with variable path (file inclusion risk)',
    ],
    'weak_hash' => [
        'pattern' => '/\b(md5|sha1)\s*\(\s*\$_/',
        'severity' => 'MEDIUM',
        'message' => 'Weak hash function on user input',
    ],
    'raw_password_compare' => [
        'pattern' => '/\$\w+\s*==+\s*\$_(POST|GET|REQUEST)\[[\'"][^\'"]*[\'"]\]/',
        'severity' => 'MEDIUM',
        'message' => 'Loose comparison on user input (use === and password_verify)',
    ],
    'sql_exec_raw' => [
        'pattern' => '/\bexec\s*\(\s*\$_/',
        'severity' => 'CRITICAL',
        'message' => 'Raw exec of user input',
    ],
];

$files = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($ROOT, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($iterator as $file) {
    $path = $file->getPathname();
    $rel = str_replace($ROOT . DIRECTORY_SEPARATOR, '', $path);
    $parts = explode(DIRECTORY_SEPARATOR, $rel);
    if (in_array($parts[0], $EXCLUDE_DIRS, true)) {
        continue;
    }
    if ($file->isFile() && strtolower($file->getExtension()) === 'php') {
        $files[] = $path;
    }
}

$findings = [];
$scanned = 0;

foreach ($files as $file) {
    $code = @file_get_contents($file);
    if ($code === false) {
        continue;
    }
    $scanned++;
    $rel = str_replace($ROOT . DIRECTORY_SEPARATOR, '', $file);
    $lines = explode("\n", $code);
    $lineOffset = 0;

    foreach ($rules as $ruleId => $rule) {
        $count = 0;
        if (preg_match_all($rule['pattern'], $code, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $m) {
                $count++;
                $lineNo = $lineOffset + 1 + substr_count(substr($code, 0, $m[1]), "\n");
                $findings[] = [
                    'rule' => $ruleId,
                    'severity' => $rule['severity'],
                    'message' => $rule['message'],
                    'file' => $rel,
                    'line' => $lineNo,
                ];
            }
        }
    }
}

// Sort by severity
$severityOrder = ['CRITICAL' => 0, 'HIGH' => 1, 'MEDIUM' => 2, 'LOW' => 3];
usort($findings, function ($a, $b) use ($severityOrder) {
    return $severityOrder[$a['severity']] <=> $severityOrder[$b['severity']];
});

echo "==============================================\n";
echo " webERP Security Scan\n";
echo "==============================================\n";
echo "Scanned files:  " . $scanned . "\n";
echo "Findings:       " . count($findings) . "\n\n";

$bySeverity = [];
foreach ($findings as $f) {
    $bySeverity[$f['severity']] = ($bySeverity[$f['severity']] ?? 0) + 1;
}
foreach ($severityOrder as $sev => $order) {
    if (isset($bySeverity[$sev])) {
        echo sprintf("  %-8s : %d\n", $sev, $bySeverity[$sev]);
    }
}

echo "\n--- Top findings (first 50) ---\n";
$show = array_slice($findings, 0, 50);
foreach ($show as $f) {
    printf(" [%s] %s:%d\n    %s\n", $f['severity'], $f['file'], $f['line'], $f['message']);
}
if (count($findings) > 50) {
    echo " ... and " . (count($findings) - 50) . " more findings.\n";
}

$hasCritical = isset($bySeverity['CRITICAL']);
$hasHigh = isset($bySeverity['HIGH']);

echo "\n" . ($hasCritical || $hasHigh ? "RESULT: FINDINGS DETECTED (exit 1)\n" : "RESULT: NO CRITICAL/HIGH FINDINGS (exit 0)\n");

exit($hasCritical || $hasHigh ? 1 : 0);
