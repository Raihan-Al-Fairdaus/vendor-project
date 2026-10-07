<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Status Pendaftaran Vendor</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #1b3a60;
            padding: 25px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
        }
        .header span {
            color: #ef4444; /* red */
        }
        .content {
            padding: 30px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        .status-approved {
            background-color: #dcfce7;
            color: #16a34a;
            border: 1px solid #16a34a;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #dc2626;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
        .btn {
            display: inline-block;
            background-color: #1b3a60;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DNA <span>Vendor</span> Portal</h1>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $vendor->company_name }}</strong>,</p>

            @if($status === 'approved')
                <p>Selamat! Kami informasikan bahwa pendaftaran Anda sebagai mitra vendor di <strong>DNA Advertising</strong> telah <strong>DISETUJUI</strong>.</p>
                
                <div class="status-badge status-approved">
                    Status: DISETUJUI (APPROVED)
                </div>

                <p>Tim kami akan segera menghubungi Anda melalui nomor kontak yang terdaftar ({{ $vendor->company_phone }}) untuk langkah selanjutnya mengenai kerjasama penyewaan billboard.</p>
                
                <p>Terima kasih telah bergabung menjadi bagian dari jaringan DNA Advertising.</p>

            @else
                <p>Terima kasih atas ketertarikan Anda untuk mendaftar sebagai mitra vendor di <strong>DNA Advertising</strong>.</p>
                
                <p>Setelah melakukan peninjauan terhadap data dan dokumen yang Anda kirimkan, dengan berat hati kami sampaikan bahwa pendaftaran Anda saat ini <strong>BELUM DAPAT KAMI SETUJUI</strong>.</p>
                
                <div class="status-badge status-rejected">
                    Status: DITOLAK (REJECTED)
                </div>

                <p>Hal ini dapat disebabkan oleh kelengkapan dokumen yang kurang sesuai, spesifikasi titik billboard yang belum memenuhi kriteria kami saat ini, atau hal lainnya.</p>
                
                <p>Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk membalas email ini.</p>
            @endif

            <p style="margin-top: 30px;">Hormat kami,<br><strong>Tim Kemitraan DNA Advertising</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} DNA Advertising Network. Hak cipta dilindungi undang-undang.<br>
            Email ini dikirim secara otomatis, harap jangan membalas ke alamat email pengirim (No-Reply).
        </div>
    </div>
</body>
</html>
