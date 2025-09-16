# Teacher Profile Filter

[![Moodle 4.4+](https://img.shields.io/badge/Moodle-4.4+-orange.svg)](https://moodle.org/)
[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

A Moodle text filter that automatically renders teacher profile information anywhere in course content using simple placeholder syntax.

## 🎯 Purpose

This filter was created to provide a standardized way to display teacher/instructor information throughout Moodle courses without manual copying or maintenance. It enables course creators to embed rich teacher profiles that automatically update when teacher information changes.

## 🚀 Features

- **Simple Syntax**: Use `[[course_teacherprofile]]` placeholder anywhere in text
- **Automatic Detection**: Finds course teachers by capability or role
- **Specific User Targeting**: Target specific users with `[[course_teacherprofile:12]]`
- **Rich Profile Display**: Shows fullname, picture, description, and custom fields
- **Custom Fields Integration**: Display custom user profile fields
- **Flexible Configuration**: Configurable teacher roles and displayed fields

## 📝 Usage

### Basic Usage
```
[[course_teacherprofile]]
```
Automatically finds the first user with `moodle/course:changefullname` capability or the role defined in filter settings.

### Specific User
```
[[course_teacherprofile:12]]
```
Displays profile for user with ID 12.

### Where to Use
The filter works in any text area that supports filters:
- Course descriptions
- Section descriptions
- Activity descriptions
- Block content
- Page/Label content
- Forum posts
- And more...

## 🛠️ Installation

### Prerequisites
- Moodle 4.4 or higher
- Administrative access to Moodle

### Installation Steps

1. **Download the Plugin**
   ```bash
   cd /path/to/moodle/filter/
   git clone https://github.com/bmbrands/moodle-filter_teacherprofile.git teacherprofile
   ```

2. **Install via Moodle Admin**
   - Log in as administrator
   - Navigate to **Site administration** → **Notifications**
   - Complete the installation process

3. **Enable the Filter**
   - Go to **Site administration** → **Plugins** → **Filters** → **Manage filters**
   - Enable "Teacher Profile" filter
   - Set to "On" or "On but disabled by default"

## ⚙️ Configuration

### Filter Settings
Navigate to: **Site administration** → **Plugins** → **Filters** → **Teacher Profile**

#### Teacher Profile Custom Role
- **Setting**: Custom role for teacher detection
- **Description**: Define which role to search for when auto-detecting teachers
- **Default**: Uses `moodle/course:changefullname` capability if not set

#### Teacher Quality Standard
- **Setting**: Custom user profile fields to display
- **Description**: Comma-separated list of custom profile field shortnames
- **Example**: `qualification,experience,specialization,bio`

### Custom User Profile Fields Setup

1. **Create Custom Fields**
   - Go to **Site administration** → **Users** → **User profile fields**
   - Add custom fields for teacher information:
     - Qualification
     - Experience
     - Specialization
     - Teaching Philosophy
     - etc.

2. **Configure Field Display**
   - Add the field shortnames to "Teacher Quality Standard" setting
   - Fields will appear in the teacher profile display

## 🎨 Profile Display

The filter renders a formatted profile box containing:

```
┌─────────────────────────────────────┐
│ [Teacher Photo] Teacher Name        │
│                                     │
│ Description/Bio text...             │
│                                     │
│ Qualification: PhD in Mathematics   │
│ Experience: 10 years               │
│ Specialization: Algebra            │
└─────────────────────────────────────┐
```

## 🔧 Technical Details

### Automatic Teacher Detection
1. Searches for users with `moodle/course:changefullname` capability
2. Falls back to role defined in "Teacher Profile Custom Role" setting
3. Returns first matching user found

### Performance
- Results are cached per course context
- Minimal database queries through efficient capability checking
- Responsive design adapts to container width

## 🎯 Use Cases

### Course Information Pages
Display consistent teacher information across all course sections:
```
## About Your Instructor
[[course_teacherprofile]]

Welcome to our course! Above you can see information about your instructor.
```

### Welcome Messages
```
Welcome to the course!

[[course_teacherprofile]]

I'm looking forward to working with you this semester.
```

### Multiple Teachers
```
## Course Team

**Lead Instructor:**
[[course_teacherprofile:15]]

**Teaching Assistant:**
[[course_teacherprofile:23]]
```

## 🔍 Troubleshooting

### Filter Not Working
1. Verify filter is enabled: **Site administration** → **Plugins** → **Filters**
2. Check filter is active in course context
3. Ensure teacher has appropriate role/capability

### No Teacher Found
1. Verify teacher is enrolled in course
2. Check teacher has required capability or role
3. Review "Teacher Profile Custom Role" setting

### Custom Fields Not Showing
1. Confirm custom profile fields exist
2. Check field shortnames in "Teacher Quality Standard" setting
3. Verify teacher has filled out custom fields

### Profile Display Issues
1. Clear Moodle caches: **Site administration** → **Development** → **Purge caches**
2. Check theme compatibility
3. Verify filter syntax is correct

## 🔧 Development

### Testing
```bash
# Run filter tests
vendor/bin/phpunit filter/teacherprofile/tests/
```

### Filter Processing
The filter processes text during Moodle's standard filter pipeline, replacing placeholders with rendered profile HTML.

## 📋 Compatibility

- **Moodle**: 4.4+
- **PHP**: 8.1+
- **Database**: MySQL, PostgreSQL, MariaDB
- **Themes**: Compatible with all standard Moodle themes

## 🤝 Integration

### Works Great With
- **format_mawang**: Course format that can display teacher profiles
- **theme_mawang**: Optimized styling for teacher profile display
- Any custom theme with profile styling

## 📄 License

GNU GPL v3 or later

## 🐛 Support

- **Issues**: https://github.com/bmbrands/moodle-filter_teacherprofile/issues
- **Documentation**: See this README
- **Moodle Forums**: Tag with "filter_teacherprofile"
