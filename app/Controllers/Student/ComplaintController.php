<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\ComplaintModel;
use App\Models\NoteModel;

class ComplaintController extends BaseController
{
    protected $complaintModel;
    protected $noteModel;

    public function __construct()
    {
        $this->complaintModel = new ComplaintModel();
        $this->noteModel = new NoteModel();
    }

    /**
     * عرض جميع بلاغات الطالب
     */
    public function index()
    {
        $userId = session()->get('user_id');
        
        $data = [
            'title'      => 'بلاغاتي',
            'pageTitle'  => 'بلاغاتي',
            'complaints' => $this->complaintModel->getByUser($userId),
            'statuses'   => ComplaintModel::getStatuses(),
        ];

        return view('student/complaints/index', $data);
    }

    /**
     * صفحة إنشاء بلاغ جديد
     */
    public function create()
    {
        $data = [
            'title'      => 'إرسال بلاغ جديد',
            'pageTitle'  => 'إرسال بلاغ جديد',
            'categories' => ComplaintModel::getCategories(),
            'priorities' => ComplaintModel::getPriorities(),
        ];

        return view('student/complaints/create', $data);
    }

    /**
     * حفظ بلاغ جديد
     */
    public function store()
    {
        $rules = [
            'title'       => 'required|min_length[5]|max_length[255]',
            'description' => 'required|min_length[10]',
            'category'    => 'required',
            'priority'    => 'required|in_list[low,medium,high,urgent]',
        ];

        $messages = [
            'title' => [
                'required'   => 'عنوان البلاغ مطلوب',
                'min_length' => 'العنوان يجب أن يكون 5 أحرف على الأقل',
            ],
            'description' => [
                'required'   => 'وصف البلاغ مطلوب',
                'min_length' => 'الوصف يجب أن يكون 10 أحرف على الأقل',
            ],
            'category' => [
                'required' => 'نوع البلاغ مطلوب',
            ],
            'priority' => [
                'required' => 'أولوية البلاغ مطلوبة',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // معالجة رفع الملف المرفق
        $attachment = null;
        $file = $this->request->getFile('attachment');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/complaints', $newName);
            $attachment = $newName;
        }

        $complaintData = [
            'user_id'     => session()->get('user_id'),
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'category'    => $this->request->getPost('category'),
            'priority'    => $this->request->getPost('priority'),
            'status'      => 'open',
            'attachment'  => $attachment,
        ];

        if ($this->complaintModel->insert($complaintData)) {
            return redirect()->to('/student/complaints')->with('success', 'تم إرسال البلاغ بنجاح!');
        }

        return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إرسال البلاغ');
    }

    /**
     * عرض تفاصيل بلاغ
     */
    public function show($id)
    {
        $userId = session()->get('user_id');
        $complaint = $this->complaintModel->getWithUser($id);

        // التحقق من ملكية البلاغ
        if (!$complaint || $complaint['user_id'] != $userId) {
            return redirect()->to('/student/complaints')->with('error', 'البلاغ غير موجود');
        }

        $data = [
            'title'     => 'تفاصيل البلاغ',
            'pageTitle' => 'تفاصيل البلاغ #' . $id,
            'complaint' => $complaint,
            'notes'     => $this->noteModel->getByComplaint($id, false),
            'statuses'  => ComplaintModel::getStatuses(),
            'priorities' => ComplaintModel::getPriorities(),
        ];

        return view('student/complaints/show', $data);
    }

    /**
     * تقييم البلاغ بعد الحل
     */
    public function rate($id)
    {
        $userId = session()->get('user_id');
        $complaint = $this->complaintModel->find($id);

        // التحقق من ملكية البلاغ
        if (!$complaint || $complaint['user_id'] != $userId) {
            return redirect()->to('/student/complaints')->with('error', 'البلاغ غير موجود');
        }

        // التحقق من أن البلاغ تم حله
        if ($complaint['status'] !== 'resolved') {
            return redirect()->back()->with('error', 'لا يمكن تقييم بلاغ لم يتم حله بعد');
        }

        // التحقق من عدم وجود تقييم سابق
        if (!empty($complaint['rating'])) {
            return redirect()->back()->with('error', 'تم تقييم هذا البلاغ مسبقاً');
        }

        // التحقق من صحة البيانات
        $rating = $this->request->getPost('rating');
        if (!$rating || $rating < 1 || $rating > 5) {
            return redirect()->back()->with('error', 'يرجى اختيار تقييم صحيح (1-5)');
        }

        // حفظ التقييم
        $updateData = [
            'rating'         => (int) $rating,
            'rating_comment' => $this->request->getPost('rating_comment'),
            'rated_at'       => date('Y-m-d H:i:s'),
        ];

        if ($this->complaintModel->update($id, $updateData)) {
            return redirect()->back()->with('success', 'شكراً لتقييمك! نسعى دائماً لتحسين خدماتنا.');
        }

        return redirect()->back()->with('error', 'حدث خطأ أثناء حفظ التقييم');
    }
}
