const previewGalleryFiles = () => {
  const input = document.getElementById("galleryInput");
  const output = document.getElementById("galleryFileList");
  if (input.files.length > 0) {
    output.innerHTML = `✅ Đã chọn <b>${input.files.length}</b> ảnh.`;
  } else {
    output.innerHTML = "";
  }
};

const addSpecRow = () => {
  const table = document
    .getElementById("specTable")
    .getElementsByTagName("tbody")[0];
  const newRow = table.insertRow();
  const index = new Date().getTime();
  newRow.innerHTML = `
                <td><input type="text" name="specs[${index}][name]" placeholder="Tên thông số"></td>
                <td><input type="text" name="specs[${index}][value]" placeholder="Giá trị"></td>
                <td style="text-align:center;"><button type="button" class="btn btn-danger-sm" onclick="this.closest('tr').remove()">✕</button></td>
            `;
};

const createContentBlockHTML = (prefix, placeholderText) => {
  const index = new Date().getTime() + Math.floor(Math.random() * 1000);
  return `
                <div class="content-item">
                    <button type="button" class="btn-remove" onclick="this.closest('.content-item').remove()" title="Xóa khối này">✕</button>
                    <div class="img-upload-area">
                        <div class="upload-box" onclick="document.getElementById('${prefix}_file_${index}').click()">
                            <div class="upload-placeholder" id="${prefix}_placeholder_${index}">
                                <svg viewBox="0 0 24 24"><path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-4.86 8.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z"/></svg>
                                <div style="font-size: 12px;">Chọn ảnh</div>
                            </div>
                            <img src="" id="${prefix}_preview_${index}" class="img-preview">
                            <input type="file" id="${prefix}_file_${index}" name="${prefix}_blocks[${index}][image]" accept="image/*" style="display:none" onchange="previewGenericImage(this, '${prefix}', '${index}')">
                        </div>
                    </div>
                    <div class="text-area-wrapper">
                        <textarea name="${prefix}_blocks[${index}][text]" placeholder="${placeholderText}"></textarea>
                    </div>
                </div>
            `;
};

const previewGenericImage = (input, prefix, index) => {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById(`${prefix}_placeholder_${index}`).style.display =
        "none";
      const img = document.getElementById(`${prefix}_preview_${index}`);
      img.src = e.target.result;
      img.style.display = "block";
    };
    reader.readAsDataURL(input.files[0]);
  }
};

const addUsesBlock = () => {
  document
    .getElementById("usesContainer")
    .insertAdjacentHTML(
      "beforeend",
      createContentBlockHTML("uses", "Nhập công dụng...")
    );
};
const addUsageBlock = () => {
  document
    .getElementById("usageContainer")
    .insertAdjacentHTML(
      "beforeend",
      createContentBlockHTML("usage", "Hướng dẫn bước này...")
    );
};
const addNewBlock = () => {
  document
    .getElementById("contentContainer")
    .insertAdjacentHTML(
      "beforeend",
      createContentBlockHTML("content", "Viết mô tả chi tiết...")
    );
};
const addReviewBlock = () => {
  document
    .getElementById("reviewContainer")
    .insertAdjacentHTML(
      "beforeend",
      createContentBlockHTML("review", "Nhập feedback khách hàng...")
    );
};

const form = document.getElementById("productForm");
form.addEventListener("submit", function () {
  const btn = document.getElementById("btnSave");
  btn.classList.add("loading");
  btn.style.pointerEvents = "none";
});

document.addEventListener("DOMContentLoaded", function () {
  addUsesBlock(); // Section 3
  addUsageBlock(); // Section 4
  addNewBlock(); // Section 5
  addReviewBlock(); // Section 6
});

const checkQuickAdd = (selectElement, type) => {
  if (selectElement.value === "create_new") {
    selectElement.style.display = "none";

    const inputId = type === "brand" ? "input_new_brand" : "input_new_category";
    const inputElement = document.getElementById(inputId);
    inputElement.style.display = "block";
    inputElement.value = "";
    inputElement.focus();
  }
};

const handleEnter = (e, inp, type) => {
  if (e.key === "Enter") {
    // 1. Validate dữ liệu
    if (!inp.value.trim()) {
      alert("Vui lòng nhập tên cần tạo!");
      return;
    }

    let formData = new FormData();
    formData.append("type", type);
    formData.append("name", inp.value);

    inp.disabled = true;
    inp.placeholder = "Đang lưu...";

    fetch("ajax_quick_add.php", {
      method: "POST",
      body: formData,
    })
      .then((r) => r.json())
      .then((d) => {
        if (d.success) {
          const wrapperId = type === "brand" ? "wrapper_brand" : "wrapper_cat";
          let sel = document.querySelector(`#${wrapperId} select`);

          if (sel) {
            let opt = new Option(d.name, d.id, true, true);
            sel.add(opt, sel.options[1]);

            sel.style.display = "block";
          }

          inp.style.display = "none";
          inp.disabled = false;
          inp.placeholder = "Nhập tên & Enter...";
          showToast(`Đã thêm thành công  ${d.name}`, "success");
        } else {
          console.log(" Lỗi: " + d.message);
          inp.disabled = false;
          inp.placeholder = "Nhập tên & Enter...";
          inp.focus();
        }
      })
      .catch((err) => {
        console.error(err);
        console.error(" Lỗi kết nối server!");
        inp.disabled = false;
      });
  }
};

const saveQuickData = (type, name, inputElement) => {
  const formData = new FormData();
  formData.append("type", type);
  formData.append("name", name);

  inputElement.placeholder = "Đang lưu...";
  inputElement.disabled = true;

  fetch("ajax_quick_add.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const wrapperId = type === "brand" ? "wrapper_brand" : "wrapper_cat";
        const selectElement = document.querySelector(`#${wrapperId} select`);

        const newOption = document.createElement("option");
        newOption.value = data.id;
        newOption.text = data.name;
        newOption.selected = true;

        selectElement.insertBefore(
          newOption,
          selectElement.querySelector('option[value="create_new"]')
        );

        revertToSelect(inputElement, selectElement);
        showToast(`Đã thêm thành công sản phẩm ${data.name}`, "success");
      } else {
        console.log("Lỗi: " + data.message);

        revertToSelect(inputElement, null); // Reset nếu lỗi
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      console.error("Có lỗi xảy ra khi kết nối server");
      revertToSelect(inputElement, null);
    });
};

const cancelQuickAdd = (inputElement, type) => {
  setTimeout(() => {
    if (!inputElement.disabled) {
      const wrapperId = type === "brand" ? "wrapper_brand" : "wrapper_cat";
      const selectElement = document.querySelector(`#${wrapperId} select`);

      if (selectElement.value === "create_new") {
        selectElement.value = "";
      }
      revertToSelect(inputElement, selectElement);
    }
  }, 200);
};

const revertToSelect = (inputElement, selectElement) => {
  inputElement.style.display = "none";
  inputElement.value = "";
  inputElement.disabled = false;
  inputElement.placeholder = "Nhập tên mới rồi ấn Enter...";

  if (selectElement) {
    selectElement.style.display = "block";
  } else {
    inputElement.parentElement.querySelector("select").style.display = "block";
  }
};
