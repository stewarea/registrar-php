<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Students.php";
    require_once __DIR__."/../src/Courses.php";

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    $app = new Silex\Application();

    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=registrar';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function () use ($app){
        return $app['twig']->render('index.html.twig');
    });

    $app->get("/courses", function () use ($app){
        return $app['twig']->render('courses.html.twig', array('courses' => Courses::getAll()));
    });

    $app->post("/courses", function () use ($app){
        $new_course = new Courses($_POST['name'], $_POST['start'], 1);
        $new_course->save();
        // return var_dump($new_course);
        return $app['twig']->render('courses.html.twig', array('courses' => Courses::getAll()));
    });

    $app->get("/students", function () use ($app){
        return $app['twig']->render('students.html.twig', array('students' => Students::getAll()));
    });

    $app->post("/students", function () use ($app){
        $new_student = new Students($_POST['name'], $_POST['major']);
        $new_student->save();
        return $app['twig']->render('students.html.twig', array('students' => Students::getAll()));
    });

    // $app->get("/students/{id}", function($id) use ($app) {
    //     $student = Students::find($id);
    //     return $app['twig']->render('student.html.twig', array('student' => $student, 'courses' => $student->getCourses()));
    // });

    $app->get("/students/{id}", function($id) use ($app) {
        $student = Students::find($id);
        return $app['twig']->render('student.html.twig', array('student' => $student, 'courses' => $student->getCourses(), 'courses2' => $student->getNonCourses(), 'all_courses' => Courses::getAll()));
    });

    $app->get("/courses/{id}", function($id) use ($app) {
        $course = Courses::find($id);
        return $app['twig']->render('course.html.twig', array('course' => $course, 'students' => $course->getStudents(), 'students2' => $course->getNonStudents(), 'all_students' => Students::getAll()));
    });

    $app->post("/course/{id}", function ($id) use ($app){
        $course = Courses::find($id);
        $new_student = Students::find($_POST['student_id']);
        $course ->addStudent($new_student);

        return $app['twig']->render('course.html.twig', array('course' => $course, 'students' => $course->getStudents(), 'students2' => $course->getNonStudents(), 'all_students' => Students::getAll()));
    });

    $app->post("/student/{id}", function ($id) use ($app){
        $student = Students::find($id);
        $new_course = Courses::find($_POST['course_id']);
        $student->addCourses($new_course);

        return $app['twig']->render('student.html.twig', array('student' => $student, 'courses' => $student->getCourses(), 'courses2' => $student->getNonCourses(), 'all_courses' => Courses::getAll()));
    });

    $app->post("/upload_file", function () use ($app){
        if(isset($_POST['btn-upload']))
        {
         $file = rand(1000,100000)."-".$_FILES['file']['name'];
         $file_loc = $_FILES['file']['tmp_name'];
         $file_size = $_FILES['file']['size'];
         $file_type = $_FILES['file']['type'];
         $folder="uploads/";

         move_uploaded_file($file_loc,$folder.$file);
         $GLOBALS['DB']->exec("INSERT INTO tbl_uploads(file,type,size) VALUES('{$file}','{$file_type}','{$file_size}')");

        //  $new_photo = Students->getPhoto();
        //  $new_photo->save();

        }


        return $app['twig']->render('index.html.twig');
    });



    return $app;

    ?>
