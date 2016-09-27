<?php
  class Courses
  {
    private $id;
    private $name;
    private $start;
    private $department;


    function __construct($name, $start, $department, $id = null)
      {
        $this->id = $id;
        $this->name = $name;
        $this->start = $start;
        $this->department = $department;
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

          function setDepartment($new_department)
            {
                $this->department =  $new_department;
            }

            function getDepartment()
            {
                return $this->department;
            }


        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO courses (name, start, department) VALUES ('{$this->getName()}', '{$this->getStart()}', '{$this->getDepartment()}');");
            $this->id= $GLOBALS['DB']->lastInsertId();
        }
        static function getAll()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses;");
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

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM courses;");
          $GLOBALS['DB']->exec("DELETE FROM courses_students;");
        }
        static function find($search_id)
        {
            $found_course = null;
            $courses = Courses::getAll();
            foreach($courses as $course) {
                $course_id = $course->getId();
                if ($course_id == $search_id) {
                  $found_course = $course;
                }
            }
            return $found_course;
        }

        function addStudent($student)
        {

            $GLOBALS['DB']->exec("INSERT INTO courses_students (course_id, student_id) VALUES ({$this->getId()}, {$student->getId()});");
        }




        function getStudents()
        {
            $returned_students = $GLOBALS['DB']->query("SELECT students.* FROM students JOIN courses_students ON (students.id = courses_students.student_id) JOIN courses ON (courses.id = courses_students.course_id);");

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






  }

 ?>
