<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận email</title>
</head>
<body style="background-color: #f8f9fa; font-family: Arial, sans-serif; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h1 style="text-align: center;">Xác nhận email</h1>
        <p style="text-align: center; font-size: 18px;">Vui lòng nhấp vào liên kết dưới đây để xác nhận tài khoản của bạn:</p>
        <p style="text-align: center;">
            <a href="{{ url('api/auth/verify-email/' . $token) }}" style="display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Xác nhận tài khoản</a>
        </p>
        <p style="text-align: center; font-size: 16px; margin-top: 20px;">Lưu ý: Liên kết này chỉ có hiệu lực trong 30 phút. Sau khoảng thời gian này, bạn sẽ cần yêu cầu một liên kết xác nhận mới.</p>
    </div>
</body>
</html>
