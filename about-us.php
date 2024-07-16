<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .about-section {
            padding: 50px 0;
        }

        .about-heading {
            font-size: 30px;
            margin-bottom: 30px;
            text-align: center;
        }

        .project-details {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }

        .team-member-card {
            margin-bottom: 30px;
        }

        .team-member-img {
            width: 100%;
            height : 100%;
            max-height: 20rem;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .team-member-details {
            padding: 20px;
            border: 1px solid #dee2e6;
            /* border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px; */
            border-radius: 10px;
            background-color: #ffffff;
        }

        .back-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .group-img {
            width: 100%;
            border-radius: 10px 10px 0 0;
        }
        .group-img:hover {
            transition: 1s;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
            if(!isset($_SESSION['login_id'])){
                echo "<button class='back-button btn btn-primary' onclick='loginPage()'>Back</button>";
            }
        ?>
        <div class="row about-section">

            <div class="col-md-6">
            <div style="overflow: hidden;">
                <img src="assets/group_img.jpg" class="group-img" style="width:100%" alt="group_img">
            </div>
            
                <div class="project-details">
                    
                    <h2 class="about-heading">Project Details</h2>
                        <p>The MCA final year project on a file tracking system under the guidance of 
                           <b> Prof. Samir Srivastava </b> and <b>Asst. Prof. Ananya Rathore </b> aimed to address the challenges 
                            associated with file management in organizations. This project was undertaken 
                            with the objective of designing and implementing an efficient system to track 
                            the movement and location of files within an organization, thereby enhancing 
                            productivity and reducing the risk of document loss..</p>
                        <p>
                        The project involved extensive research into existing file management systems and 
                        technologies.Under the guidance of Prof. Samir Srivastava, the project team developed a 
                        comprehensive system architecture and user interface that incorporated features 
                        such as file tagging, real-time tracking, access control, and reporting functionalities.
                        Throughout the project, mentors provided invaluable guidance and support, offering 
                        insights into industry trends and advising on technical challenges. Their mentorship ensured the 
                        successful completion of the project within the stipulated timeframe and to the highest standards of quality.
                        </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row" style="justify-content:center;">
                    <h4 >Team Members</h4>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="team-member-card">
                            <div class="team-member-details">
                                <h4>Prof. Samir Srivastava</h4>
                                <p>Computer Science and Engineering Department</p>
                                <p>samir@knit.ac.in</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="team-member-card">
                            <div class="team-member-details">
                            <h4>Prof. Ananya Rathore</h4>
                                <p>Computer Science and Engineering Department</p>
                                <p>annoo@knit.ac.in</p>    
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="team-member-card">
                            
                            <div class="team-member-details">
                            <h4>Mrinal Kumar</h4>
                                <p>Master of Computer Applications , 2nd Year</p>
                                <p>mrinal.22735@knit.ac.in</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="team-member-card">
                           
                            <div class="team-member-details">
                            <h4>Abhay Singh</h4>
                                <p>Master of Computer Applications , 2nd Year</p>
                                <p>abhay.22702@knit.ac.in</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="team-member-card">
                            
                            <div class="team-member-details">
                            <h4>Avinash Kumar<br> Verma</h4>
                                <p>Master of Computer Applications , 2nd Year</p>
                                <p>avinash.22712@knit.ac.in</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="team-member-card">
                            
                            <div class="team-member-details">
                                <h4>Bhanu Pratap Singh</h4>
                                <p>Master of Computer Applications , 1st Year</p>
                                <p>bhanu.23715@knit.ac.in</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="team-member-card">
                            
                            <div class="team-member-details">
                            <h4>Harish Kumar</h4>
                                <p>Master of Computer Applications , 2nd Year</p>
                                <p>harish.22722@knit.ac.in</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="team-member-card">
                        
                            <div class="team-member-details">
                                <h4>Shivam Ray</h4>
                                <p>Master of Computer Applications , 1st Year</p>
                                <p>shivam.23759@knit.ac.in</p>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</body>
<script>
function loginPage(){
    location.href = 'index.php';
}
</script>
</html>
