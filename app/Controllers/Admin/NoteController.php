<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NoteModel;
use App\Models\ComplaintModel;

class NoteController extends BaseController
{
    protected $noteModel;
    protected $complaintModel;

    public function __construct()
    {
        $this->noteModel = new NoteModel();
        $this->complaintModel = new ComplaintModel();
    }

    /**
     * إضافة ملاحظة على بلاغ
     */
    public function add($complaintId)
    {
        $content = $this->request->getPost('content');
        $isInternal = $this->request->getPost('is_internal') ? true : false;

        if (empty($content)) {
            return redirect()->back()->with('error', 'محتوى الملاحظة مطلوب');
        }

        $complaint = $this->complaintModel->find($complaintId);
        if (!$complaint) {
            return redirect()->to('/admin/complaints')->with('error', 'البلاغ غير موجود');
        }

        if ($this->noteModel->addNote($complaintId, session()->get('user_id'), $content, $isInternal)) {
            return redirect()->back()->with('success', 'تم إضافة الملاحظة بنجاح');
        }

        return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الملاحظة');
    }
}
