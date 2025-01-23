# SiBimbinganSkripsi

SiBimbinganSkripsi is a web application designed to monitor final assignments at Universitas Teknologi Digital Indonesia (UTDI). This application aims to facilitate students, lecturers, and academic staff in managing the supervision process for final assignments.

## Features
- **Student Data Management:** Integration of student data, final assignments, and supervision status in one system.
- **Manuscript Submission:** Students can upload their assignment drafts, which are directly accessible to lecturers for evaluation.
- **Progress Monitoring:** Real-time dashboard to track the progress of final assignments.
- **Automatic Notifications:** Alerts for supervision schedules and revision deadlines.
- **Schedule Management:** Students can arrange supervision schedules, while lecturers can approve or modify them.
- **Academic Report Generation:** Evaluation reports and supervision statistics for administrative purposes.
- **Data Security:** Student data is protected with encryption and role-based authentication.

## Technologies Used
- **Frontend:** HTML5, CSS, JavaScript
- **Backend:** PHP (Laravel)
- **Database:** MySQL

## How to Run the Application
1. Download the repository or run the command `git clone link_project` via CMD or Terminal.
2. Extract the downloaded project folder.
3. Navigate to the extracted folder using CMD or Terminal.
4. Run the command `composer install`. Ensure XAMPP (or similar) and Composer are installed beforehand.
5. Rename the file `.env.example` to `.env`.
6. Run the command `php artisan migrate:fresh --seed` via CMD or Terminal.
7. Run the command `php artisan storage:link` via CMD or Terminal.
8. Run the command `php artisan key:generate` via CMD or Terminal.
9. Start the application by running `php artisan serve` via CMD or Terminal.
10. Open the provided link using your preferred browser.
11. Log in using the email (admin@gmail.com) and password (password).

## Contribution
If you wish to contribute to this project, please submit a pull request. I welcome suggestions and improvements from the community.

## Source
The original repository can be found on [GitHub](https://github.com/restumahesa26/si_bimbingan_skripsi.git).

