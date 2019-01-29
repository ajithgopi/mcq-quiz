# mcq-quiz
Local MCQ quiz web application

## Installation
1. Copy all the files to the root directory of your hosting space
2. Create a database and import *quiz.sql*
3. Update the database credentials in *includes/connection.php*


    $_host		=	"YOUR HOST NAME";
    $_username	=	"YOUR DATABASE USERNAME";
    $_password	=	"YOUR DATABASE PASSWORD";
    $_db_name	=	"YOUR DATABASE NAME";
    
4. Installation completed. Now you can login the admin panel with the default username and password -- _admin:password123_

## Documentation
All the documentation are available in each section of the admin panel and demo excel sheets to import questions are also provided.


#### Note
This is targeted as local web application which can also be used for online exams. Although, sufficient invigilation is recommended, especially if the candidates have a technical background. Also note that only one exam can be conducted at a time, and the active exam can be switched in the admin panel.

## License
This project is licensed under BSD 3-Clause License - See the [LICENSE](LICENSE) file for further details.
