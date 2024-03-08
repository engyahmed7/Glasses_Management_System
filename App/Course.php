<?php
namespace App;

class Course{

    /**
    * Get Course List
    * @return array
    */

    public function getCourses(): array{
        $xml=new \SimpleXMLElement('<items/>');
        $xml->addChild('name','php');
        $xml->addChild('lessons','5');

        return [
            $xml
        ];
    }
    
    /**
     * Get Single Course
     * @param int $id
     * @return array
     */
    
    public function getCourseById(int $id): array{
        
        return [];
    }
    
    /**
     * Get Course Lessons
     * @param int $courseId
     * @return array
     */
    
    public function getLessons(int $courseId): array{
        
        return [];
    }
}

