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


    return $app;

    ?>
