// Hàm hiển thị thông báo Toast xịn xò
function showToast(message, type = "success") {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end", // Hiện ở góc trên phải
    showConfirmButton: false, // Không cần nút OK
    timer: 3000, // Tự tắt sau 3 giây
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener("mouseenter", Swal.stopTimer);
      toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
  });

  Toast.fire({
    icon: type, // success, error, warning, info
    title: message,
  });
}
