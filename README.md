The Ashesi Rides Management System allows users to book seats on buses, view available rides, and submit reviews. Admins can manage users, bookings, and monitor reviews.
Before installing and using the app, ensure the following:
PHP (Version 7.4 or higher)
MySQL (Database for storing application data)
Web Server (Apache or Nginx)
Browser (Google Chrome, Firefox, etc.)

Example email for login, 
admin: naana@gmail.com
password: Naana123#

regular admin: nana@gmail.com
password: Nana123#

Clone the repository
Import the SQL file to set up the database:
Update ../db/config.php with your database credentials.
Start the server: Use a web server like XAMPP, WAMP, or MAMP to host the application. Place the project files in the server root (e.g., htdocs for XAMPP).
Access the application: Open a browser and navigate to:
Admins can access the admin panel by logging in with admin credentials.

Admin Functionalities:
Dashboard: Overview of users, bookings, and reviews.
Manage Users: Add, edit, or delete users.
Manage Bookings: Add new rides, edit existing bookings, or remove unnecessary records.
Manage Reviews: View all reviews.
Users can access the public pages to view rides, book seats, and submit reviews.

User Functionalities:
View Available Rides: On the homepage, see ride details (e.g., route, fare, date).
Reserve Seats: Select seats on the bus and confirm reservations.
Submit Reviews: After a ride, leave a star rating (1-5) and a comment.

