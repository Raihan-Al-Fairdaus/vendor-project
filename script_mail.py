import re

with open(r'C:\laragon\www\vendor-project\app\Http\Controllers\Admin\VendorManagementController.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Add use statements if not exist
if 'use Illuminate\Support\Facades\Mail;' not in content:
    content = content.replace('use App\Models\Vendor;', 'use App\Models\Vendor;\nuse Illuminate\Support\Facades\Mail;\nuse App\Mail\VendorStatusMail;')

# Replace approve logic
old_approve = '''    public function approve()
    {
         = Vendor::findOrFail();

        ->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Vendor approved successfully.');
    }'''

new_approve = '''    public function approve()
    {
         = Vendor::findOrFail();

        ->update([
            'status' => 'approved'
        ]);

        try {
            Mail::to(->vendor_email)->send(new VendorStatusMail(, 'approved'));
        } catch (\Exception ) {
            return back()->with('success', 'Vendor approved successfully. Namun email gagal dikirim: ' . ->getMessage());
        }

        return back()->with('success', 'Vendor approved and email sent successfully.');
    }'''

content = content.replace(old_approve, new_approve)

# Replace reject logic
old_reject = '''    public function reject()
    {
         = Vendor::findOrFail();

        ->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Vendor rejected successfully.');
    }'''

new_reject = '''    public function reject()
    {
         = Vendor::findOrFail();

        ->update([
            'status' => 'rejected'
        ]);

        try {
            Mail::to(->vendor_email)->send(new VendorStatusMail(, 'rejected'));
        } catch (\Exception ) {
            return back()->with('success', 'Vendor rejected successfully. Namun email gagal dikirim: ' . ->getMessage());
        }

        return back()->with('success', 'Vendor rejected and email sent successfully.');
    }'''

content = content.replace(old_reject, new_reject)

with open(r'C:\laragon\www\vendor-project\app\Http\Controllers\Admin\VendorManagementController.php', 'w', encoding='utf-8') as f:
    f.write(content)
