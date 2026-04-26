<?php

namespace App\Http\Controllers;

use App\Mail\FacultyAccountCreated;
use App\Models\RecentActivity;
use App\Models\Role;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function index()
    {
        $studentCount = User::where('role', 'student')->count();
        $facultyCount = User::where('role', 'faculty')->count();
        $roleCount = Role::count();
        $sectionCount = Section::count();

        $studentJoin = RecentActivity::where('action', 'A student joined to this section')->latest()->take(6)->get();

        $roleCountPerRole = DB::table('roles')
            ->leftJoin('activity_user_role', 'roles.id', '=', 'activity_user_role.role_id')
            ->select('roles.name as role_name', DB::raw('COUNT(activity_user_role.role_id) as total'))
            ->groupBy('roles.name')
            ->get();

        $faculty = User::where('role','faculty')->get();

        return view('admin.dashboard', compact('facultyCount', 'studentCount', 'roleCount', 'sectionCount', 'studentJoin', 'roleCountPerRole', 'faculty'));
    }

    public function faculty()
    {
        $faculty = User::where('role', 'faculty')->get();

        return view('admin.faculty', compact('faculty'));
    }

    public function facultyCreateAccount(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'username' => 'required|string|max:255|unique:users,username',
                'password' => 'required|string|min:4|confirmed',
            ]);

            // Generate unique profile image
            $folder = storage_path('app/public/profile_images');

            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            $imageName = 'user_' . time() . '_' . rand(1000, 9999) . '.png';
            $destination = $folder . '/' . $imageName;

            // Create 200x200 PNG
            $img = imagecreatetruecolor(200, 200);

            // Random background color
            $bgColor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
            imagefilledrectangle($img, 0, 0, 200, 200, $bgColor);

            // White text color
            $textColor = imagecolorallocate($img, 255, 255, 255);

            // User initials
            $initials = strtoupper(substr($request->name, 0, 1));

            // Path to TTF font
            $font = public_path('fonts/arial.ttf');
            $fontSize = 60;

            // Center the initials
            $bbox = imagettfbbox($fontSize, 0, $font, $initials);
            $textWidth = $bbox[2] - $bbox[0];
            $textHeight = $bbox[1] - $bbox[7];
            $x = (200 - $textWidth) / 2;
            $y = (200 + $textHeight) / 2;

            imagettftext($img, $fontSize, 0, $x, $y, $textColor, $font, $initials);
            imagepng($img, $destination);
            imagedestroy($img);

            // Create the user with profile_image
            $password = $request->password ?? 'pass123'; // Use form input if provided, otherwise default
            $user = User::create([
                'name' => $request->name,
                'role' => 'faculty',
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($password),
                'profile_image' => $imageName,
            ]);

            Mail::to($user->email)->send(new FacultyAccountCreated($user, $password));

            return redirect()->back()->with('success', 'Faculty account created and credentials sent!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create faculty account: ' . $e->getMessage()]);
        }
    }

    public function facultyUpdateDetail(Request $request)
    {
        $faculty = User::findOrFail($request->faculty_id);
        $faculty->update([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ]);

        return redirect()->back()->with('success', 'Faculty updated successfully.');
    }

    public function facultyDeleteDetail(Request $request)
    {
        $faculty = User::findOrFail($request->faculty_id);
        $faculty->delete();

        return redirect()->back()->with('success', 'Faculty deleted successfully.');
    }

    public function students()
    {
        $students = User::where('role', 'student')->paginate(6);

        return view('admin.students', compact('students'));
    }

    public function sections()
    {
        $sections = Section::with('user')->orderBy('created_at','desc')->paginate(6);

        return view('admin.sections', compact('sections'));
    }
}
