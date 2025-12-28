<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ComplaintModel;
use App\Models\NoteModel;
use App\Models\UserModel;
use App\Models\ActivityLogModel;
use App\Models\NotificationModel;

class ComplaintController extends BaseController
{
    protected $complaintModel;
    protected $noteModel;
    protected $userModel;
    protected $activityLogModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->complaintModel = new ComplaintModel();
        $this->noteModel = new NoteModel();
        $this->userModel = new UserModel();
        $this->activityLogModel = new ActivityLogModel();
        $this->notificationModel = new NotificationModel();
    }

    /**
     * عرض جميع البلاغات
     */
    public function index()
    {
        // معالجة الفلاتر
        $status = $this->request->getGet('status');
        $category = $this->request->getGet('category');
        $priority = $this->request->getGet('priority');

        $complaints = $this->complaintModel->filter($status, $category, $priority);

        $data = [
            'title'      => 'إدارة البلاغات',
            'pageTitle'  => 'إدارة البلاغات',
            'complaints' => $complaints,
            'statuses'   => ComplaintModel::getStatuses(),
            'categories' => ComplaintModel::getCategories(),
            'priorities' => ComplaintModel::getPriorities(),
            'filters'    => [
                'status'   => $status,
                'category' => $category,
                'priority' => $priority,
            ],
        ];

        return view('admin/complaints/index', $data);
    }

    /**
     * عرض تفاصيل بلاغ
     */
    public function show($id)
    {
        $complaint = $this->complaintModel->getWithUser($id);

        if (!$complaint) {
            return redirect()->to('/admin/complaints')->with('error', 'البلاغ غير موجود');
        }

        $data = [
            'title'        => 'تفاصيل البلاغ',
            'pageTitle'    => 'تفاصيل البلاغ #' . $id,
            'complaint'    => $complaint,
            'notes'        => $this->noteModel->getByComplaint($id, true),
            'statuses'     => ComplaintModel::getStatuses(),
            'priorities'   => ComplaintModel::getPriorities(),
            'admins'       => $this->userModel->getAdmins(),
            'activityLogs' => $this->activityLogModel->getByComplaint($id),
        ];

        return view('admin/complaints/show', $data);
    }

    /**
     * تحديث حالة البلاغ
     */
    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        
        if (!in_array($status, ['open', 'in_progress', 'resolved', 'closed'])) {
            return redirect()->back()->with('error', 'حالة غير صالحة');
        }

        $complaint = $this->complaintModel->find($id);
        if (!$complaint) {
            return redirect()->to('/admin/complaints')->with('error', 'البلاغ غير موجود');
        }

        $oldStatus = $complaint['status'];

        // تعيين الإداري إذا لم يكن معين
        $updateData = ['status' => $status];
        if (empty($complaint['assigned_to'])) {
            $updateData['assigned_to'] = session()->get('user_id');
        }
        
        if ($status === 'resolved' || $status === 'closed') {
            $updateData['resolved_at'] = date('Y-m-d H:i:s');
        }

        if ($this->complaintModel->update($id, $updateData)) {
            // إضافة ملاحظة تلقائية
            $statusNames = ComplaintModel::getStatuses();
            $this->noteModel->addNote(
                $id,
                session()->get('user_id'),
                'تم تغيير حالة البلاغ إلى: ' . $statusNames[$status],
                false
            );
            
            // تسجيل النشاط
            $this->activityLogModel->logStatusChange($id, session()->get('user_id'), $oldStatus, $status);
            
            // إرسال إشعار للطالب صاحب البلاغ
            $this->notificationModel->notifyStatusChange($id, $complaint['user_id'], $oldStatus, $status);
            
            return redirect()->back()->with('success', 'تم تحديث حالة البلاغ بنجاح');
        }

        return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الحالة');
    }

    /**
     * إضافة رد على البلاغ
     */
    public function respond($id)
    {
        $response = $this->request->getPost('response');
        
        if (empty($response)) {
            return redirect()->back()->with('error', 'الرد مطلوب');
        }

        $complaint = $this->complaintModel->find($id);
        if (!$complaint) {
            return redirect()->to('/admin/complaints')->with('error', 'البلاغ غير موجود');
        }

        // تحديث رد الإداري
        $this->complaintModel->update($id, [
            'admin_response' => $response,
            'assigned_to'    => session()->get('user_id'),
        ]);

        // إضافة ملاحظة
        $this->noteModel->addNote($id, session()->get('user_id'), $response, false);

        return redirect()->back()->with('success', 'تم إرسال الرد بنجاح');
    }

    /**
     * تعيين البلاغ لإداري
     */
    public function assign($id)
    {
        $adminId = $this->request->getPost('admin_id');
        
        $complaint = $this->complaintModel->find($id);
        if (!$complaint) {
            return redirect()->to('/admin/complaints')->with('error', 'البلاغ غير موجود');
        }

        if ($this->complaintModel->update($id, ['assigned_to' => $adminId])) {
            return redirect()->back()->with('success', 'تم تعيين البلاغ بنجاح');
        }

        return redirect()->back()->with('error', 'حدث خطأ أثناء التعيين');
    }
}
