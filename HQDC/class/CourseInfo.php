<?php
class CourseInfo {
    private $_courseIntroduce;
    private $_teachingEnvironment;
    private $_teachingProgram;
    private $_db;
    private $_tableName;
    public function __construct() {
        $this->_tableName = 'course_info';
        $this->_db = DB::getInstance();
    }
    public function update($context) {
        if (!$this->_db->get($this->_tableName)) {
            $this->_db->insert($this->_tableName, $context);
            return true;
        }
        else {
            $this->_db->update($this->_tableName, 1, $context);
        }	        	
    }
    
    public function get(){
        $res = $this->_db->get($this->_tableName);
        $res = $res->first();
        
        $this->_courseIntroduce = $res->course_introduce;
        $this->_teachingEnvironment = $res->teaching_environment;
        $this->_teachingProgram = $res->teaching_program;
       // echo  $this->_teachingProgram;
    }
    
    public function getCourseIntroduce() {
        $this->get();
        return $this->_courseIntroduce;
    }
    
    public function getTeachingEnvironment() {
        $this->get();
        return $this->_teachingEnvironment;
    }
    
    public function getTeachingProgram() {
        $this->get();
        return $this->_teachingProgram;
    }
}