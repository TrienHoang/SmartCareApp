document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".container");
    const formType = container.dataset.formType;

    if (formType === 'register') {
        container.classList.add("sign-up-mode");
    }

    const sign_in_btn = document.querySelector("#sign-in-btn");
    const sign_up_btn = document.querySelector("#sign-up-btn");
    const sign_in_btn2 = document.querySelector("#sign-in-btn2");
    const sign_up_btn2 = document.querySelector("#sign-up-btn2");

    // Thêm chế độ đăng ký khi nhấn vào nút "Sign Up"
    sign_up_btn.addEventListener("click", () => {
        container.classList.add("sign-up-mode");
    });

    // Loại bỏ chế độ đăng ký khi nhấn vào nút "Sign In"
    sign_in_btn.addEventListener("click", () => {
        container.classList.remove("sign-up-mode");
    });

    // Thêm chế độ đăng ký khi nhấn vào liên kết "Sign Up" trong phần account text
    sign_up_btn2.addEventListener("click", () => {
        container.classList.add("sign-up-mode");
    });

    // Quay lại chế độ đăng nhập khi nhấn vào liên kết "Sign In" trong phần account text
    sign_in_btn2.addEventListener("click", () => {
        container.classList.remove("sign-up-mode");
    });
});
