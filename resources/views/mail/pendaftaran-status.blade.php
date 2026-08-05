<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran {{ $status === 'disetujui' ? 'Disetujui' : 'Ditolak' }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 32px 32px 16px 32px; text-align: center;">
                            <div style="display: inline-block; padding: 6px 16px; border-radius: 999px; font-size: 13px; font-weight: bold; letter-spacing: 0.5px; color: {{ $status === 'disetujui' ? '#ffffff' : '#ffffff' }}; background-color: {{ $status === 'disetujui' ? '#059669' : '#dc2626' }};">
                                {{ $status === 'disetujui' ? 'DISETUJUI' : 'DITOLAK' }}
                            </div>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding: 16px 32px 8px 32px;">
                            <h2 style="margin: 0 0 8px 0; font-size: 22px; color: #111827;">{{ $namaSekolah }}</h2>
                            <p style="margin: 0 0 4px 0; font-size: 14px; color: #6b7280;">NPSN: {{ $npsn }}</p>
                            <p style="margin: 0 0 4px 0; font-size: 14px; color: #6b7280;">{{ $jenjang }} &middot; {{ $provinsi }}, {{ $kabupatenKota }}</p>
                        </td>
                    </tr>
                    <!-- Catatan Admin -->
                    <tr>
                        <td style="padding: 16px 32px 8px 32px;">
                            <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: bold; color: #374151;">Catatan dari admin:</p>
                            <div style="background-color: #f9fafb; border-left: 4px solid {{ $status === 'disetujui' ? '#059669' : '#dc2626' }}; border-radius: 8px; padding: 16px;">
                                <p style="margin: 0; font-size: 14px; color: #374151; line-height: 1.6; white-space: pre-line;">{{ $catatanAdmin }}</p>
                            </div>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 32px 32px 32px; text-align: center; border-top: 1px solid #e5e7eb; margin-top: 16px;">
                            <p style="margin: 0; font-size: 12px; color: #9ca3af;">
                                Email ini dikirim otomatis oleh sistem SatuPeta. Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
