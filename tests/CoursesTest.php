<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Courses.php";
    require_once 'src/Students.php';

    $server = 'mysql:host=localhost;dbname=registrar';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CoursesTest extends PHPUnit_Framework_TestCase
    {
      protected function tearDown()
        {
          Courses::deleteAll();
          Students::deleteAll();
        }
        function test_getName()
        {
            //Arrange
            $name = "History";
            $start = "12:00:00";
            $department = 1;

            $test_Course = new Courses($name, $start, $department);

            //Act
            $result = $test_Course->getName();

            //Assert
            $this->assertEquals($name, $result);
        }
        function test_save()
        {
            //Arrange
            $name = "History";
            $start = "12:00:00";
            $department = 1;

            $test_Course = new Courses($name, $start, $department);
            $test_Course->save();

            //Act
            $result = Courses::getAll();

            //Assert
            $this->assertEquals($test_Course, $result[0]);
        }
        function test_getAll()
        {
            //Arrange
            $name = "History";
            $start = "12:00:00";
            $department = 1;
            $name2 = "Biology";
            $start2 = "01:00:00";
            $department2 = 2;
            $test_Course = new Courses($name, $start, $department);
            $test_Course->save();
            $test_Course2 = new Courses($name, $start, $department);
            $test_Course2->save();

            //Act
            $result = Courses::getAll();

            //Assert
            $this->assertEquals([$test_Course, $test_Course2], $result);
        }
        function test_deleteAll()
        {
            //Arrange
            $name = "History";
            $start = "12:00:00";
            $department = 1;
            $name2 = "Biology";
            $start2 = "01:00:00";
            $department2 = 2;
            $test_Course = new Courses($name, $start, $department);
            $test_Course->save();
            $test_Course2 = new Courses($name, $start, $department);
            $test_Course2->save();

            //Act
            Courses::deleteAll();
            $result = Courses::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $name = "History";
            $start = "12:00:00";
            $department = 1;
            $name2 = "Biology";
            $start2 = "01:00:00";
            $department2 = 2;
            $test_Course = new Courses($name, $start, $department);
            $test_Course->save();
            $test_Course2 = new Courses($name, $start, $department);
            $test_Course2->save();

            //Act
            $result = Courses::find($test_Course->getId());

            //Assert
            $this->assertEquals($test_Course, $result);
        }

        function test_addStudent()
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

            $test_Course->addStudent($test_Student);
            $result = $test_Course->getStudents();
            $this->assertEquals([$test_Student], $result);
        }

        function test_NonStudents()
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

            $name2 = "Rob Bones";
            $major2 = 3;
            $test_Student2 = new Students($name2, $major2);
            $test_Student2->save();

            $test_Course->addStudent($test_Student);

            $result = $test_Course->getNonStudents();
            $this->assertEquals([$test_Student2], $result);
        }





    }

  ?>
