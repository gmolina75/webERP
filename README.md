# webERP - Accounting & Business Administration ERP System

## Overview

webERP is a complete web-based open-source accounting, business management and ERP (Enterprise Resource Planning) system built for businesses of all sizes. This is the PHP v4.1 version with the initial setup ready for customization and deployment.

**License:** GPL-2.0-only  
**Minimum PHP:** ^8.1  
**Database:** MySQLi (required), MariaDB, PostgreSQL (deprecated)

## Key Features

### 🆓 Open Source & Free
- 100% Free & Open Source under GNU Public License v2
- No licensing fees, no hidden costs

### 🌐 Web-Based Access
- Access from anywhere using any modern browser
- No software installation required
- Works on any web server that supports PHP

### 🛠️ Highly Customizable
- Written in readable PHP code with clear coding standards
- Modify to fit unique business needs
- Full source code available for editing

### 📦 Comprehensive Module Suite

#### Accounting & Finance
- General Ledger with double-entry bookkeeping
- Trial Balance, Profit & Loss, Balance Sheet reports
- Bank & Cash reconciliation
- Multi-currency support with auto-updated exchange rates
- Standard & Weighted Average Costing
- Tax management with flexible categories
- Automated voucher postings and number generation

#### Inventory & Stock Control
- Unlimited warehouses/locations
- Serial and Lot tracking inventory
- Stock quantity maintenance across multiple locations
- Stock movement history and traceability
- Reorder level management

#### Sales & Orders
- Quotations and Sales Orders management
- Convert quotes to orders upon customer acceptance
- Multiple price lists and discount support
- Currency pricing per sales type
- Order tracking from creation to shipping to invoicing

#### Purchasing
- Purchase Order creation and management
- Receive goods with quantity adjustments
- Supplier billing and payment tracking
- Vendor performance tracking

#### Manufacturing & Production
- Multi-level Bills of Materials (BOM)
- Work Order costing and scheduling
- MRP (Material Requirements Planning)
- Production cost roll-up on BOM/component changes
- Ghost Bills and Kitsets/Assemblies

#### Customer & Supplier Management
- Customer accounts with transaction history
- Supplier accounts with ledger tracking
- Contact management
- Payment allocation and tracking

#### Analytics & Reporting
- Sales analysis and customer insights
- Inventory valuation and stock status
- Financial reporting (P&L, Balance Sheet, Cash Flow)
- Custom report generation
- Graphs and visual data representation

### 🌍 Multi-Language & Multi-Currency
- 37+ language support (UTF-8 MB4 character set)
- Multiple currency support with auto-updated rates
- Tax rates dependent on product type, location, and customer

### 👥 User Access & Security
- Role-based access control
- Login tracking and security logging
- Module-level permissions
- Audit trails for all transactions

### 📊 Additional Features
- PDF report generation (TCPDF & DomPDF)
- Email functionality (phpmailer integration)
- Geocoding integration
- File attachment support (EDI, forms)
- Dashboard system with customizable widgets
- Commission tracking for agents
- Regular payments processing
- Context-sensitive help system
- Session management with automatic restoration

### 🔧 Technical Requirements
- PHP ^8.1 with extensions: ctype, mbstring, mysqli, xml, zip, gd, bcmath, calendar, ftp, simplexml, sqlite3, zlib, iconv
- MySQL 5.7.5+ or MariaDB 5.5+ (InnoDB required)
- Web server (Apache HTTP Server or Nginx)
- Recommended: XAMPP for development Windows platform

### 📦 What's Included in This Repository
- 2817+ PHP files covering all ERP modules
- SQL database schema (sql/demo.sql, sql/config.sql)
- CSS themes (default, xenos, fluid, arc-dark, etc.)
- JavaScript functionality
- 37+ language locale files
- Report writer and form designer
- MRP/Planning modules
- Fixed asset management
- Inventory valuation tools
- Payment processing modules

## Quick Start

1. **Database Setup:** Create a MySQL/MariaDB database
2. **Copy Files:** Upload to your web server directory
3. **Configure:** Edit config.distrib.php → config.php with DB credentials
4. **Install:** Access via browser and run web installer
5. **Setup Company:** Copy companies/weberpdemo to companies/yourcompany
6. **Login:** Use admin/weberp credentials (change default password)

## Documentation
- CHANGELOG.md - Complete change history
- INSTALL.md - Detailed installation instructions
- UPGRADING.md - Upgrade guide
- README.md - This file

## Development
- Follow PHP coding standards in the existing codebase
- Use Composer for dependency management
- Test changes in a development environment first
- Respect the multi-language locale structure

## Contributing
This is the starting point for webERP customization. Fork the repository, create branches for features, and submit pull requests.

---
*Initialized from webERP v4.1 source code. For latest features and updates, visit https://www.weberp.org/*