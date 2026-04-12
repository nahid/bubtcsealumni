# Role: Senior Full-Stack Laravel Developer
# Task: Build an MVP for "AlumVibe", a CSE Alumni Networking App

## Tech Stack
- Backend: PHP 8.5, Laravel 12
- Database: PostgreSQL
- Frontend: Blade Templates, jQuery (for AJAX/Interactions), Tailwind CSS
- Styling: Modern, clean "Community Vibe" (use Inter or Geist fonts, subtle rounded corners, and soft shadows)

## Core Logic & Features to Implement

### 1. Verification-Link Authentication
- Custom Registration: Name, Email, Mobile, Password, Intake (Batch), Shift (Day/Evening), and two Reference_Email.
- Status Workflow: Accounts are 'pending' by default.
- Reference Logic: If User A registers with User B & C as a reference, User B & C sees a "Pending Approvals" list.
- Action: If User B & C approves, User A is 'verified' and can login. If declined, the registration is deleted/rejected.

### 2. Job Board System
- Job Posting: Fields for Title, External Link, Salary (optional), Expiry Date, Status (Open/Closed), and Tags (comma-separated).
- Job Exploration: Searchable list with filtering by tags.
- Tag Subscriptions: Users can "Follow" specific tags (e.g., #Laravel, #DataScience).
- Notifications: Trigger a notification when a new job matches a user's subscribed tags.

### 3. Community Directory
- Alumni Search: A searchable directory of verified alumni.
- Profiles: User profile pages including a profile photo, bio, and "Reach Out" buttons (Direct WhatsApp link and Mailto link).

### 4. Admin Notices
- Simple CRUD for Admin to post Events and Notices.
- Display these as a feed on the user Dashboard.

## UI/UX Instructions
- Use a "Modern Community" aesthetic: Dark mode support, high-contrast typography, and intuitive navigation.
- Use jQuery for:
    - Real-time tag filtering in Job Explore.
    - Approval/Decline actions in the Reference dashboard (no page refresh).
    - Submitting profile updates via AJAX.

## First Step
Please initialize the Laravel 12 project, set up the Migration for the Users table (including the reference and verification status fields), and create the custom Registration logic.
