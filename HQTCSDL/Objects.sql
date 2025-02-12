USE PHARMACY;

--1. Xay dung view
CREATE VIEW v_SLThuocNCC 
AS
SELECT ncc.ma_NCC, ncc.ten_NCC,
	COUNT( DISTINCT nt.ma_thuoc) AS so_loai_thuoc,
	SUM(t.gia_ban) AS tong_gia_ban
FROM NhaCungCap ncc JOIN NCC_Thuoc nt ON nt.ma_NCC = ncc.ma_NCC
					JOIN Thuoc t ON nt.ma_thuoc = t.ma_thuoc
GROUP BY ncc.ma_NCC, ncc.ten_NCC;

SELECT * FROM v_SLThuocNCC;

drop view v_SLDonCuaNV
CREATE VIEW v_SLDonCuaNV
AS
	SELECT nv.ma_NV, nv.ten_NV, COUNT(hd.ma_HD) as so_hoa_don,
	FROM NhanVien nv
	join HoaDon hd on nv.ma_NV = hd.ma_NV
	join ChiTietHD cthd on hd.ma_HD = cthd.ma_HD
	join Thuoc t on cthd.ma_Thuoc = t.ma_thuoc
	GROUP BY nv.ma_NV, nv.ten_NV
	HAVING COUNT(hd.ma_HD) > 0;
	
SELECT * FROM v_SLDonCuaNV;

drop view v_LichSuMuaKH;
CREATE VIEW v_LichSuMuaKH
AS
	SELECT kh.ma_KH,kh.ten_KH,kh.SDT_KH,kh.gioi_tinh, COUNT(hd.ma_HD) as So_don_hang,
	SUM(cthd.so_luong * t.gia_ban) as Tong_chi_tieu, MAX(hd.ngay_tao) AS ngay_mua_gan_nhat
	FROM KhachHang kh   
	LEFT JOIN HoaDon hd on kh.ma_KH = hd.ma_KH
	LEFT JOIN ChiTietHD  cthd on hd.ma_HD = cthd.ma_HD
	LEFT JOIN Thuoc t on cthd.ma_Thuoc = t.ma_thuoc
	GROUP BY kh.ten_KH,kh.ma_KH,kh.SDT_KH,kh.gioi_tinh
	HAVING COUNT(hd.ma_HD) > 0;

SELECT AVG(So_don_hang) AS trung_binh_so_don_hang, 
	   AVG(Tong_chi_tieu) AS trung_binh_tong_chi_tieu
FROM v_LichSuMuaKH;

drop view ChiTietHoaDonView
CREATE VIEW ChiTietHoaDonView AS
SELECT 
    ct.ma_HD,t.ten_thuoc AS ten_thuoc_view,
	ct.so_luong,t.gia_ban,
    (ct.so_luong * t.gia_ban) AS thanh_tien
FROM ChiTietHD ct
JOIN Thuoc t ON ct.ma_Thuoc = t.ma_Thuoc;

SELECT * FROM ChiTietHoaDonView;

--2. Xay dung procudure
CREATE PROCEDURE sp_SLThuocNCC
    @MaNCC INT = NULL,
    @MinSL INT = 0,
    @MaxSL INT = 1000
AS
BEGIN
	SELECT ncc.ma_NCC,  ncc.ten_NCC,
		COUNT(DISTINCT nt.ma_thuoc) AS so_loai_thuoc,
		SUM(t.gia_ban) AS tong_gia_ban
	FROM NhaCungCap ncc JOIN NCC_Thuoc nt ON nt.ma_NCC = ncc.ma_NCC
						JOIN Thuoc t ON nt.ma_thuoc = t.ma_thuoc
	GROUP BY ncc.ma_NCC, ncc.ten_NCC
	HAVING (@MaNCC IS NULL OR ncc.ma_NCC = @MaNCC) AND
			COUNT(DISTINCT nt.ma_thuoc) BETWEEN @MinSL AND @MaxSL;
END;

EXEC sp_SLThuocNCC;
EXEC sp_SLThuocNCC @MinSL = 5, @MaxSL = 20;
EXEC sp_SLThuocNCC @MaNCC = 15, @MinSL = 3, @MaxSL = 10;
EXEC sp_SLThuocNCC @MaNCC = 11;

DROP PROCEDURE sp_SLThuocNCC;

CREATE PROCEDURE ThemNhanVien
    @ma_NV INT,
    @ten_NV NVARCHAR(100),
    @SDT NVARCHAR(15),
    @dia_chi NVARCHAR(50),
    @ngay_sinh DATE
AS
BEGIN
    INSERT INTO NhanVien (ma_NV, ten_NV, SDT, dia_chi, ngay_sinh)
    VALUES (@ma_NV, @ten_NV, @SDT, @dia_chi, @ngay_sinh);
END;

EXEC ThemNhanVien 
    @ma_NV = 11, 
    @ten_NV = N'Ngô Phương Linh', 
    @SDT = '0909123456', 
    @dia_chi = N'Hà Nội', 
    @ngay_sinh = '1997-01-11';

DROP PROC sp_LichSuMuaKH;
CREATE PROC sp_LichSuMuaKH
	@TenKhachHang nvarchar(100) = '%',
	@StartDate date = NULL,
	@EndDate date = NULL
AS 
	BEGIN
		SELECT kh.ma_KH,kh.ten_KH,kh.SDT_KH, COUNT(hd.ma_HD) as So_don_hang,
		SUM(cthd.so_luong * t.gia_ban) as Tong_chi_tieu, MAX(hd.ngay_tao) AS ngay_mua_gan_nhat
		FROM KhachHang kh
		LEFT JOIN HoaDon hd on kh.ma_KH = hd.ma_KH
		LEFT JOIN ChiTietHD  cthd on hd.ma_HD = cthd.ma_HD
		LEFT JOIN Thuoc t on cthd.ma_Thuoc = t.ma_thuoc
		WHERE kh.ten_KH LIKE @TenKhachHang
			  AND (@StartDate IS NULL OR hd.ngay_tao >= @StartDate) 
			  AND (@EndDate IS NULL OR hd.ngay_tao <= @EndDate)
		GROUP BY kh.ten_KH,kh.ma_KH,kh.SDT_KH
		HAVING COUNT(hd.ma_HD) > 0;
	END;

EXEC sp_LichSuMuaKH;
EXEC sp_LichSuMuaKH @TenKhachHang = N'%Nguyễn%';
EXEC sp_LichSuMuaKH @TenKhachHang = N'%Nguyễn%', @StartDate = '2024-01-01', @EndDate = '2024-06-06';

Drop proc ThemThuoc
CREATE PROCEDURE dbo.ThemThuoc
(
    @ma_thuoc INT,
    @ten_thuoc NVARCHAR(100), 
    @thuong_hieu NVARCHAR(100),
    @lieu_luong VARCHAR(50),
    @so_luong_ton INT,
    @gia_nhap DECIMAL(10,2),
    @gia_ban DECIMAL(10,2),
    @HSD DATE,
    @ma_NCC INT,  
    @result NVARCHAR(255) OUTPUT  
)
AS
BEGIN
    IF EXISTS (
        SELECT 1
        FROM Thuoc t
        JOIN NCC_Thuoc nt ON t.ma_thuoc = nt.ma_thuoc
        WHERE t.ten_thuoc = @ten_thuoc AND nt.ma_NCC = @ma_NCC
    )
    BEGIN
        SET @result = N'Error: Thuốc này đã tồn tại trong hệ thống của nhà cung cấp.';
        RETURN;
    END

    INSERT INTO Thuoc (ma_thuoc, ten_thuoc, thuong_hieu, lieu_luong, so_luong_ton, gia_nhap, gia_ban, HSD)
    VALUES (@ma_thuoc, @ten_thuoc, @thuong_hieu, @lieu_luong, @so_luong_ton, @gia_nhap, @gia_ban, @HSD);

    INSERT INTO NCC_Thuoc (ma_NCC, ma_thuoc)
    VALUES (@ma_NCC, @ma_thuoc);

    SET @result = N'Thuốc đã được thêm thành công.';
END;

DECLARE @result NVARCHAR(255);

EXEC dbo.ThemThuoc 
    @ma_thuoc = 165,
    @ten_thuoc = N'Iron',
    @thuong_hieu = N'Dược Phẩm DEF',
    @lieu_luong = '500mg',
    @so_luong_ton = 100,
    @gia_nhap = 20.00,
    @gia_ban = 30.00,
    @HSD = '2025-12-31',
    @ma_NCC = 12,
    @result = @result OUTPUT;

SELECT @result AS Result;

--3. Xay dung function
CREATE FUNCTION dbo.fn_TrungBinhSoLoaiThuoc()
RETURNS DECIMAL(10, 2)
AS
BEGIN
    DECLARE @TrungBinh DECIMAL(10, 2);
    
    SELECT @TrungBinh = AVG(so_loai_thuoc)
    FROM (
        SELECT COUNT(DISTINCT nt.ma_thuoc) AS so_loai_thuoc
        FROM NhaCungCap ncc
        JOIN NCC_Thuoc nt ON nt.ma_NCC = ncc.ma_NCC
        GROUP BY ncc.ma_NCC
    ) AS LoaiThuocNCC;

    RETURN @TrungBinh;
END;

SELECT dbo.fn_TrungBinhSoLoaiThuoc() AS TrungBinhSoLoaiThuoc;

DROP FUNCTION dbo.fn_TrungBinhSoLoaiThuoc;

DROP FUNCTION fn_TinhTongDoanhThuNhanVien
CREATE FUNCTION fn_TinhTongDoanhThuNhanVien 
	(@ma_NV INT)
	RETURNS DECIMAL(18, 2)
AS
BEGIN
    DECLARE @TongTien DECIMAL(18, 2);
    SELECT @TongTien = SUM(cthd.so_luong * t.gia_ban)
    FROM HoaDon hd
    JOIN ChiTietHD cthd ON hd.ma_HD = cthd.ma_HD
    JOIN Thuoc t ON cthd.ma_Thuoc = t.ma_thuoc
    WHERE hd.ma_NV = @ma_NV;
    
    RETURN ISNULL(@TongTien, 0); 
END;

SELECT ma_NV, ten_NV, dbo.fn_TinhTongDoanhThuNhanVien(ma_NV) AS TongDoanhThu
FROM NhanVien;

DROP FUNCTION fn_KhachHangCoHDTheoThangNam;
CREATE FUNCTION fn_KhachHangCoHDTheoThangNam
	(@thang INT = NULL,
	 @nam INT = NULL)
	RETURNS TABLE
AS
RETURN
(
    SELECT DISTINCT kh.ma_KH, kh.ten_KH, hd.ngay_tao
    FROM KhachHang kh
    JOIN HoaDon hd ON kh.ma_KH = hd.ma_KH
    WHERE (@thang IS NULL OR MONTH(hd.ngay_tao) = @thang)
		  AND (@nam IS NULL OR YEAR(hd.ngay_tao) = @nam)
);

SELECT * FROM fn_KhachHangCoHDTheoThangNam(5, NULL);
SELECT * FROM fn_KhachHangCoHDTheoThangNam(NULL, 2023);
SELECT * FROM fn_KhachHangCoHDTheoThangNam(6,2024);

CREATE FUNCTION TinhTongHoaDon (@ma_HD INT)
RETURNS DECIMAL(10, 2)
AS
BEGIN
    DECLARE @TongTien DECIMAL(10, 2);

    SELECT @TongTien = SUM(CT.so_luong * T.gia_ban)
    FROM ChiTietHD CT
    JOIN Thuoc T ON CT.ma_Thuoc = T.ma_thuoc
    WHERE CT.ma_HD = @ma_HD;

    RETURN @TongTien;
END;

SELECT dbo.TinhTongHoaDon(1) AS TongTien;
SELECT * FROM ChiTietHD;

--4. Xay dung trigger
CREATE TRIGGER trg_KiemTraSoLuongLoaiThuoc ON NCC_Thuoc
AFTER INSERT
AS
BEGIN
    DECLARE @MaNCC INT;
    DECLARE @SoLoaiThuoc INT;
    SELECT @MaNCC = ma_NCC FROM inserted;

    SELECT @SoLoaiThuoc = COUNT(DISTINCT nt.ma_thuoc)
    FROM NCC_Thuoc nt
    WHERE nt.ma_NCC = @MaNCC;

    IF @SoLoaiThuoc > 10
    BEGIN
        PRINT N'So loai thuoc đa vuot qua gioi han cho phep.';
        ROLLBACK TRANSACTION;
    END
END;

INSERT INTO NCC_Thuoc (ma_NCC, ma_thuoc) VALUES (11, 107);
INSERT INTO NCC_Thuoc (ma_NCC, ma_thuoc) VALUES (17, 111);
INSERT INTO NCC_Thuoc (ma_NCC, ma_thuoc) VALUES (19, 136);
INSERT INTO NCC_Thuoc (ma_NCC, ma_thuoc) VALUES (12, 101);

SELECT * FROM NCC_Thuoc;

DROP TRIGGER trg_KiemTraSoLuongLoaiThuoc;

CREATE TRIGGER trg_KiemTraTuoiNhanVien ON NhanVien
	FOR INSERT
AS
BEGIN
    DECLARE @ngaySinh DATE, @ten_NV NVARCHAR(100);

    SELECT @ngaySinh = i.ngay_sinh, @ten_NV = i.ten_NV
    FROM INSERTED i;

    IF DATEDIFF(YEAR, @ngaySinh, GETDATE()) < 18
    BEGIN
        RAISERROR('Nhân viên %s chưa đủ 18 tuổi. Không thể thêm vào.', 16, 1, @ten_NV);
        ROLLBACK TRANSACTION;
    END
END;

INSERT INTO NhanVien (ma_NV, ten_NV, SDT, dia_chi, ngay_sinh)
VALUES (5, N'Lê Thị Ngát', '0123487689', N'Hà Nội', '2000-10-22');

INSERT INTO NhanVien (ma_NV, ten_NV, SDT, dia_chi, ngay_sinh)
VALUES (6, N'Nguyễn Văn Ninh', '0123456789', N'Hà Nội', '2007-05-20');

SELECT * FROM NhanVien;

DROP TRIGGER tg_KtrakhiThemKhachHang;
CREATE TRIGGER tg_KtrakhiThemKhachHang 
ON KhachHang
INSTEAD OF INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1 
        FROM KhachHang KH 
        WHERE KH.SDT_KH IN (SELECT SDT_KH FROM inserted)
    )
    BEGIN
        PRINT N'Khách hàng đã có trong hệ thống!';
        ROLLBACK TRANSACTION;
        RETURN;
    END

    INSERT INTO KhachHang (ma_KH, ten_KH, SDT_KH, gioi_tinh, ngay_sinh, diem_tich)
    SELECT 
        ISNULL(i.ma_KH, (SELECT MAX(ma_KH) + 1 FROM KhachHang)), 
        i.ten_KH, i.SDT_KH,i.gioi_tinh, i.ngay_sinh,i.diem_tich
    FROM inserted i;

    COMMIT TRANSACTION;
END;

INSERT INTO KhachHang (ten_KH, SDT_KH, gioi_tinh, ngay_sinh, diem_tich) 
VALUES (N'Vũ Minh Anh', '0144446789', N'Nữ', '1997-06-05',0);

INSERT INTO KhachHang (ten_KH, SDT_KH, gioi_tinh, ngay_sinh, diem_tich) 
VALUES (N'Phạm Kim Nhung', '0144446789', N'Nữ', '2000-10-22',0);

SELECT * FROM KhachHang
WHERE ma_KH = (SELECT MAX(ma_KH) FROM KhachHang);

CREATE TRIGGER trg_GiamSoLuongTon ON ChiTietHD
AFTER INSERT
AS
BEGIN
    DECLARE @ma_thuoc INT;
    DECLARE @so_luong INT;

    SELECT @ma_thuoc = ma_thuoc, @so_luong = so_luong
    FROM inserted;

    UPDATE Thuoc
    SET so_luong_ton = so_luong_ton - @so_luong
    WHERE ma_thuoc = @ma_thuoc;
END;

INSERT INTO HoaDon (ma_HD, ma_NV, ma_KH, ngay_tao)
VALUES (095, 2, 5, GETDATE());

INSERT INTO ChiTietHD (ma_CTHD, ma_HD, ma_Thuoc, so_luong)
VALUES (095, 095, 100, 5);

SELECT so_luong_ton FROM Thuoc WHERE ma_thuoc = 100;


