<?php
  class Students
  {
    private $id;
    private $name;
    private $major;


    function __construct($name, $major, $id = null)
      {
        $this->id = $id;
        $this->name = $name;
        $this->major = $major;
      }

      function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function setStart($new_start)
          {
              $this->start =  $new_start;
          }

          function getStart()
          {
              return $this->start;
          }

          function setMajor($new_major)
            {
                $this->major =  $new_major;
            }

            function getMajor()
            {
                return $this->major;
            }


        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO students (name, major) VALUES ('{$this->getName()}', {$this->getMajor()});");
            $this->id= $GLOBALS['DB']->lastInsertId();
        }
        static function getAll()
        {
            $returned_students = $GLOBALS['DB']->query("SELECT * FROM students;");
            $students = array();
            foreach($returned_students as $student) {
                $id = $student['id'];
                $name = $student['name'];
                $major = $student['major'];
                $new_student = new Students($name, $major, $id);
                array_push($students, $new_student);
            }
            return $students;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM students;");
        }
        static function find($search_id)
        {
            $found_student = null;
            $students = Students::getAll();
            foreach($students as $student) {
                $student_id = $student->getId();
                if ($student_id == $search_id) {
                  $found_student = $student;
                }
            }
            return $found_student;
        }

        function addCourses($courses)
        {

            $GLOBALS['DB']->exec("INSERT INTO courses_students (student_id, course_id) VALUES ({$this->getId()}, {$courses->getId()});");
        }




        function getCourses()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT courses.* FROM students JOIN courses_students ON (students.id = courses_students.student_id) JOIN courses ON (courses.id = courses_students.course_id) WHERE students.id = {$this->getId()};");

            $courses = array();
            foreach($returned_courses as $course) {
                $id = $course['id'];
                $name = $course['name'];
                $start = $course['start'];
                $department = $course['department'];
                $new_course = new Courses($name, $start, $department, $id);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        function getNonCourses()
        {
            $allCourses = Courses::getAll();
            $takenCourses = $this->getCourses();

            $nonCourses = array();
            foreach($allCourses as $course) {
                if(!in_array($course, $takenCourses))
                {
                    $id = $course->getId();
                    $name = $course->getName();
                    $start = $course->getStart();
                    $department = $course->getDepartment();
                    $new_course = new Courses($name, $start, $department, $id);
                    array_push($nonCourses, $new_course);
                }
            }
            return $nonCourses;
        }



  }

 ?>
