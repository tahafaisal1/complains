<?php

namespace App\Controllers;

use App\Models\FaqModel;

class ChatbotController extends BaseController
{
    protected $faqModel;
    
    // Gemini API configuration
    private $geminiApiKey = 'AIzaSyBhtxfex6ET2YoQV1o7LCBl0gBYJoKeBS8';
    private $geminiApiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
    
    public function __construct()
    {
        $this->faqModel = new FaqModel();
        
        // Get API key from environment
        $this->geminiApiKey = getenv('GEMINI_API_KEY') ?: env('GEMINI_API_KEY', '');
    }

    /**
     * Handle chat messages
     */
    public function chat()
    {
        $message = $this->request->getPost('message');
        
        if (empty($message)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'الرسالة مطلوبة'
            ]);
        }

        // Try AI response first, fallback to FAQ if API fails
        $response = $this->getAIResponse($message);
        
        if (!$response) {
            $response = $this->getFaqResponse($message);
        }

        return $this->response->setJSON([
            'success' => true,
            'response' => $response
        ]);
    }

    /**
     * Get AI response from Gemini
     */
    private function getAIResponse($userMessage)
    {
        if (empty($this->geminiApiKey)) {
            return null; // Fallback to FAQ
        }

        try {
            // System context for the AI
            $systemPrompt = "أنت مساعد ذكي لمنصة إدارة البلاغات الجامعية. 
            مهمتك مساعدة الطلاب في:
            - كيفية إرسال بلاغات جديدة
            - متابعة حالة البلاغات
            - فهم حالات البلاغات (مفتوح، تحت المعالجة، تم الحل، مغلق)
            - الإجابة على الأسئلة الشائعة
            - التنقل في المنصة
            
            كن ودوداً ومختصراً في إجاباتك. استخدم اللغة العربية.
            إذا سُئلت عن شيء خارج نطاق المنصة، أخبر المستخدم بلطف أنك متخصص في مساعدة المنصة فقط.";

            $requestData = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\nسؤال المستخدم: " . $userMessage]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 500,
                ]
            ];

            $url = $this->geminiApiUrl . '?key=' . $this->geminiApiKey;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }
            }

            return null; // Fallback to FAQ

        } catch (\Exception $e) {
            log_message('error', 'Gemini API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get response from FAQ database (fallback)
     */
    private function getFaqResponse($userMessage)
    {
        // Predefined responses for common questions
        $predefinedResponses = [
            'مرحبا' => 'مرحباً بك! 👋 كيف يمكنني مساعدتك اليوم؟',
            'السلام' => 'وعليكم السلام! 👋 كيف يمكنني مساعدتك؟',
            'شكرا' => 'عفواً! سعيد بمساعدتك. هل هناك شيء آخر؟ 😊',
            
            'بلاغ جديد' => 'لإرسال بلاغ جديد:\n1. اضغط على "إرسال بلاغ جديد" من القائمة الجانبية\n2. أكتب عنوان ووصف البلاغ\n3. اختر نوع البلاغ والأولوية\n4. اضغط على زر الإرسال',
            
            'متابعة' => 'لمتابعة بلاغاتك:\n1. اضغط على "بلاغاتي" من القائمة\n2. ستظهر قائمة بجميع بلاغاتك\n3. اضغط على أي بلاغ لمشاهدة التفاصيل والتحديثات',
            
            'حالة' => 'حالات البلاغات:\n• مفتوح: البلاغ قيد الانتظار\n• تحت المعالجة: جاري العمل عليه\n• تم الحل: تم حل المشكلة\n• مغلق: تم إغلاق البلاغ',
            
            'تواصل' => 'يمكنك التواصل مع الإدارة عبر:\n• إرسال رسالة من صفحة تفاصيل البلاغ\n• انتظار رد الإدارة على بلاغك',
            
            'اشعارات' => 'ستصلك إشعارات عند:\n• تحديث حالة بلاغك\n• رد الإدارة على بلاغك\n• أي تعليقات جديدة',
        ];

        // Check predefined responses first
        $messageLower = mb_strtolower($userMessage);
        foreach ($predefinedResponses as $keyword => $response) {
            if (mb_strpos($messageLower, $keyword) !== false) {
                return $response;
            }
        }

        // Search in FAQ database
        $faqs = $this->faqModel->where('is_active', 1)->findAll();
        
        foreach ($faqs as $faq) {
            $questionLower = mb_strtolower($faq['question']);
            $answerLower = mb_strtolower($faq['answer']);
            
            // Check if user message matches FAQ
            if (mb_strpos($questionLower, $messageLower) !== false ||
                mb_strpos($messageLower, $questionLower) !== false ||
                $this->calculateSimilarity($messageLower, $questionLower) > 0.5) {
                return $faq['answer'];
            }
        }

        // Default response if nothing matches
        return 'عذراً، لم أتمكن من فهم سؤالك. يمكنك تجربة:\n• كيف أرسل بلاغ جديد؟\n• كيف أتابع بلاغاتي؟\n• ما هي حالات البلاغات؟\n\nأو يمكنك زيارة صفحة الأسئلة الشائعة للمزيد من المساعدة.';
    }

    /**
     * Calculate text similarity
     */
    private function calculateSimilarity($str1, $str2)
    {
        $words1 = array_unique(explode(' ', $str1));
        $words2 = array_unique(explode(' ', $str2));
        
        $intersection = count(array_intersect($words1, $words2));
        $union = count(array_unique(array_merge($words1, $words2)));
        
        return $union > 0 ? $intersection / $union : 0;
    }
}
