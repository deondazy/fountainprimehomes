<?php

namespace Okoye\Core;

class Job extends Base
{
    public $experienceList = [
        1  => 'No Experience/Less than 1 year',
        2  => '1 year',
        3  => '2 years',
        4  => '3 years',
        5  => '4 years',
        6  => '5 years',
        7  => '6 years',
        8  => '7 years',
        9  => '8 years',
        10 => '9 years',
        11 => '10 years',
        12 => '11 years',
        13 => '12 years',
        14 => '13 years',
        15 => '14 years',
        16 => '15 years',
        17 => '16 years',
        18 => '17 years',
        19 => '18 years',
        20 => '19 years',
        21 => '20 years',
        22 => 'More than 20 years',
    ];

    public $employeeType = [
        'Contract', 
        'Full time', 
        'Internship & Graduate', 
        'Part time',
    ];

    public $position = [
        'Graduate trainee',
        'Volunteer, Internship',
        'Entry level',
        'Mid level',
        'Senior level',
        'Management level',
        'Executive level',
        'No experience',
    ];

    public $qualification = [
        'Degree',
        'Diploma',
        'High School',
        'HND','MBA/MSc',
        'MBBS',
        'MPhil/PhD',
        'N.C.E',
        'OND',
        'Vocational',
        'Others',
    ];

    public function __construct($table = null)
    {
        ($table) ? $this->table = $table : parent::__construct('jobs');
    }

    public function category($id, $field)
    {
        $categoryClass = new Category;

        return $categoryClass->get($field, $id);
    }

    public function checkApplied($jobId, $userId)
    {
        $jobApplicationClass = new JobApplication;

        return $jobApplicationClass->getAll("job_id = {$jobId} && user_id = {$userId}");
    }
}
