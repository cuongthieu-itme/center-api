<?php

namespace App\Services;

use App\Repositories\TeacherRepository;
use App\Repositories\ClassModelRepository;
use App\Repositories\ClassSessionRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TeacherService
{
    protected $teacherRepository;
    protected $classModelRepository;
    protected $classSessionRepository;

    public function __construct(
        TeacherRepository $teacherRepository,
        ClassModelRepository $classModelRepository,
        ClassSessionRepository $classSessionRepository
    ) {
        $this->teacherRepository = $teacherRepository;
        $this->classModelRepository = $classModelRepository;
        $this->classSessionRepository = $classSessionRepository;
    }

    public function index()
    {
        return $this->teacherRepository->index();
    }

    public function show($id)
    {
        return $this->teacherRepository->show($id);
    }

    public function store($data)
    {
        return $this->teacherRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->teacherRepository->update($id, $data);
    }

    public function destroy($id): bool
    {
        return $this->teacherRepository->destroy($id);
    }

    public function getStudentsByTeacherId($id)
    {
        return $this->teacherRepository->getStudentsByTeacherId($id);
    }
    
    /**
     * Get classes with schedule for a teacher with pagination
     *
     * @param int $teacherId
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getTeacherClassesWithSchedule($teacherId)
    {
        // Get all classes taught by this teacher
        $classes = $this->classModelRepository->getClassesByTeacher($teacherId);
        
        $classesWithSchedule = [];
        
        foreach ($classes as $class) {
            // Get all sessions for this class
            $sessions = $this->classModelRepository->getSessions($class->id);
            
            // Filter sessions if date filters are provided
            $fromDate = request()->get('from_date');
            $toDate = request()->get('to_date');
            
            if ($fromDate || $toDate) {
                $filteredSessions = $sessions->filter(function ($session) use ($fromDate, $toDate) {
                    $sessionDate = $session->session_date;
                    
                    if ($fromDate && $sessionDate < $fromDate) {
                        return false;
                    }
                    
                    if ($toDate && $sessionDate > $toDate) {
                        return false;
                    }
                    
                    return true;
                });
                
                $sessions = $filteredSessions->values();
            }
            
            // Add class with its sessions to result
            $classesWithSchedule[] = [
                'class' => $class,
                'schedule' => $sessions
            ];
        }
        
        // Apply pagination
        $perPage = request()->get('per_page', 10);
        $page = request()->get('page', 1);
        
        // Convert to collection for pagination
        $collection = Collection::make($classesWithSchedule);
        
        // Get total items
        $total = $collection->count();
        
        // Slice the collection to get items for current page
        $items = $collection->forPage($page, $perPage);
        
        // Create a LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return $paginator;
    }
}
