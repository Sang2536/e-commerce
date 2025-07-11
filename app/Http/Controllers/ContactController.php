<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmitted;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Hiển thị form liên hệ
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * Gửi thông tin liên hệ
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($validated);

        // Gửi email thông báo
        Mail::to(config('mail.admin_address'))->send(new ContactFormSubmitted($contact));

        return redirect()->route('contact.index')
            ->with('success', 'Tin nhắn của bạn đã được gửi thành công. Chúng tôi sẽ liên hệ lại sớm!');
    }
}
