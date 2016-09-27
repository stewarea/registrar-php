<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */


    require_once "src/Students.php";
    require_once "src/Courses.php";

    $server = 'mysql:host=localhost;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StudentsTest extends PHPUnit_Framework_TestCase
    {
      protected function tearDown()
        {
          Students::deleteAll();
          Courses::deleteAll();
        }
        function test_getName()
        {
            //Arrange
            $name = "Joe Smith";
            $major = 1;

            $test_Student = new Students($name, $major);

            //Act
            $result = $test_Student->getName();

            //Assert
            $this->assertEquals($name, $result);
        }
        function test_save()
        {
            //Arrange
            $name = "Joe Smith";
            $major = 1;

            $test_Student = new Students($name, $major);
            $test_Student->save();

            //Act
            $result = Students::getAll();

            //Assert
            $this->assertEquals($test_Student, $result[0]);
        }
        function test_getAll()
        {
            //Arrange
            $name = "Joe Smith";
            $major = 1;
            $name = "Bob Jones";
            $major = 2;
            $test_Student = new Students($name, $major);
            $test_Student->save();
            $test_Student2 = new Students($name, $major);
            $test_Student2->save();

            //Act
            $result = Students::getAll();

            //Assert
            $this->assertEquals([$test_Student, $test_Student2], $result);
        }
        function test_deleteAll()
        {
            //Arrange
            $name = "Joe Smith";
            $major = 1;
            $name = "Bob Jones";
            $major = 2;
            $test_Student = new Students($name, $major);
            $test_Student->save();
            $test_Student2 = new Students($name, $major);
            $test_Student2->save();

            //Act
            Students::deleteAll();
            $result = Students::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $name = "Joe Smith";
            $major = 1;
            $name = "Bob Jones";
            $major = 2;
            $test_Student = new Students($name, $major);
            $test_Student->save();
            $test_Student2 = new Students($name, $major);
            $test_Student2->save();

            //Act
            $result = Students::find($test_Student->getId());

            //Assert
            $this->assertEquals($test_Student, $result);
        }

        function test_addCourse()
        {
            $name = "History";
            $start = "12:00:00";
            $department = 1;
            $test_Course = new Courses($name, $start, $department);
            $test_Course->save();

            $name = "Bob Jones";
            $major = 2;
            $test_Student = new Students($name, $major);
            $test_Student->save();

            $test_Student->addCourses($test_Course);
            $result = $test_Student->getCourses();
            $this->assertEquals([$test_Course], $result);
        }




    }

  ?>
