# EBOstay - Flash Tours & Travel Website

A comprehensive PHP + MySQL travel booking platform with AI-powered tour customization, Razorpay payment integration, and complete admin panel.

## Features

### 🌍 Customer Features
- **Modern Landing Page** - Beautiful hero section with search functionality
- **Package Browsing** - Browse pre-designed tour packages
- **AI Tour Customization** - Use Gemini AI to customize tours within budget
- **Smart Hotel & Activity Suggestions** - AI recommends hotels and activities
- **Coupon System** - Apply discount codes during checkout
- **Razorpay Payment Integration** - Secure payment gateway
- **Booking Management** - Track all bookings and status

### 👨‍💼 Admin Features
- **Dashboard** - Real-time statistics and analytics
- **Tour Management** - Create, edit, delete tour packages
- **Booking Tracking** - Monitor all customer bookings
- **Expense Management** - Track tour-related expenses
  - Categorized expenses (Hotel, Transport, Food, Activities)
  - Receipt uploads
  - Employee assignment
  - Breakdown by category
- **Employee Management** - Manage staff, departments, salaries
- **Coupon Management** - Create and manage discount codes
- **Analytics** - Revenue charts, booking trends, expense reports

### 🤖 AI Integration
- **Gemini API** - For intelligent tour recommendations
- **Budget-Aware Suggestions** - Hotels and activities within customer budget
- **Itinerary Generation** - Day-wise travel plans
- **Expense Optimization** - AI suggestions for cost reduction

## Tech Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Payment**: Razorpay API
- **AI**: Google Gemini API
- **Hosting**: Hostinger compatible

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer (optional)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/anubhavnig/ebo.git
   cd ebo
   ```

2. **Create Database**
   ```bash
   mysql -u root -p < database/schema.sql
   ```

3. **Configure API Keys**
   - Edit `config/gemini.php` and add your Gemini API key
   - Edit `config/razorpay.php` and add your Razorpay credentials
   - Edit `config/db.php` with your database credentials

4. **Set Permissions**
   ```bash
   chmod -R 755 /path/to/ebo
   chmod -R 777 /path/to/ebo/uploads
   ```

5. **Access the Application**
   - Visit: `http://localhost/ebo` or `http://ebostay.local`
   - Admin Panel: `/admin/dashboard.php`

## API Endpoints

### Search & Browse
- `GET /api/search-packages.php` - Search packages with filters
- `GET /api/get-activities.php` - Get activities by destination
- `GET /api/get-hotels.php` - Get hotels by destination

### Booking
- `POST /api/create-booking.php` - Create a new booking
- `POST /api/verify-payment.php` - Verify Razorpay payment
- `GET /api/get-bookings.php` - Get user's bookings

### AI Customization
- `POST /api/customize-tour.php` - Generate custom tour with Gemini
- `POST /api/suggest-activities.php` - Get AI activity suggestions
- `POST /api/suggest-hotels.php` - Get AI hotel recommendations

## Database Schema

Main tables:
- `users` - Customer and admin accounts
- `packages` - Pre-designed tour packages
- `tours` - Individual tour offerings
- `bookings` - Customer bookings
- `coupons` - Discount codes
- `activities` - Available activities
- `hotels` - Available accommodations
- `expenses` - Tour expenses tracking
- `employees` - Staff management
- `reviews` - Customer reviews
- `customize_requests` - AI customization requests

## Configuration

### Gemini API Setup
1. Get API key from [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Add to `config/gemini.php`:
   ```php
   define('GEMINI_API_KEY', 'YOUR_KEY_HERE');
   ```

### Razorpay Setup
1. Create account at [Razorpay](https://razorpay.com)
2. Get API keys from dashboard
3. Add to `config/razorpay.php`:
   ```php
   define('RAZORPAY_KEY_ID', 'YOUR_KEY_ID');
   define('RAZORPAY_KEY_SECRET', 'YOUR_KEY_SECRET');
   ```

## Usage Examples

### Customer: Book a Package
1. Search packages on homepage
2. Select a package
3. Enter travel dates and number of travelers
4. Apply coupon code (optional)
5. Complete Razorpay payment

### Customer: Customize Tour with AI
1. Click "Customize Tour" in navigation
2. Enter destination, budget, duration, preferences
3. Let Gemini AI generate itinerary
4. Review suggestions for hotels, activities, costs
5. Proceed to booking

### Admin: Track Expenses
1. Go to Admin Dashboard → Expenses
2. View expense breakdown by category
3. Add new expense with receipt
4. Assign to employee
5. Track tour profitability

## Folder Structure

```
ebo/
├── config/
│   ├── db.php              # Database configuration
│   ├── gemini.php          # Gemini AI configuration
│   ├── razorpay.php        # Razorpay configuration
│   └── constants.php       # Application constants
├── includes/
│   ├── header.php          # Page header
│   ├── footer.php          # Page footer
│   └── functions.php       # Utility functions
├── public/
│   ├── index.php           # Homepage
│   ├── css/
│   │   └── style.css       # Main stylesheet
│   └── js/
│       └── main.js         # Main JavaScript
├── api/
│   ├── search-packages.php
│   ├── customize-tour.php
│   ├── create-booking.php
│   └── verify-payment.php
├── admin/
│   ├── dashboard.php       # Admin dashboard
│   ├── manage-tours.php
│   ├── manage-bookings.php
│   ├── expenses.php        # Expense tracking
│   ├── employees.php       # Employee management
│   ├── coupons.php
│   └── analytics.php
├── pages/
│   ├── packages.php
│   ├── package-detail.php
│   ├── customize-tour.php
│   └── my-bookings.php
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
└── database/
    └── schema.sql          # Database schema
```

## Security Features

- Password hashing with bcrypt
- SQL injection prevention
- CSRF protection
- Session management
- Input validation and sanitization
- Secure payment verification

## Deployment on Hostinger

1. Upload files via FTP
2. Import database from `database/schema.sql`
3. Update `config/db.php` with Hostinger credentials
4. Set file permissions (755 for directories, 644 for files)
5. Update `SITE_URL` in `config/constants.php`
6. Install SSL certificate
7. Test payment gateway in sandbox mode first

## Support

For issues or questions:
- Email: admin@ebostay.com
- GitHub Issues: [Create an issue](https://github.com/anubhavnig/ebo/issues)

## License

MIT License - See LICENSE file for details

## Contributors

- Anubhav Nig (@anubhavnig)

## Roadmap

- [ ] Mobile app (React Native)
- [ ] Multi-language support
- [ ] Video tours integration
- [ ] Live chat support
- [ ] Email notifications
- [ ] SMS notifications
- [ ] Advanced analytics
- [ ] Loyalty program

---

**Happy Traveling! 🌍✈️**
