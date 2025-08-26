<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \App\Models\Course|null $course
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel query()
 * @mixin \Eloquent
 */
class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';
    
    protected $fillable = [
        'name',
        'course_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_students', 'class_id', 'student_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'class_users', 'class_id', 'user_id');
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class, 'class_id');
    }
}
