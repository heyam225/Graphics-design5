// تنقل بين خطوات النموذج
function nextStep(currentStep) {
    // التحقق من صحة البيانات في الخطوة الحالية
    if (currentStep === 1) {
        const selectedType = document.querySelector('.design-type.selected');
        if (!selectedType) {
            alert('يرجى اختيار نوع التصميم أولاً');
            return;
        }
        document.getElementById('projectType').value = selectedType.dataset.type;
    } else if (currentStep === 2) {
        // التحقق من صحة البيانات في الخطوة الثانية
        const requiredFields = ['clientName', 'clientEmail', 'clientPhone', 'projectName', 'projectDescription'];
        for (const field of requiredFields) {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                alert('يرجى ملء جميع الحقول المطلوبة');
                input.focus();
                return;
            }
        }
    }
    
    document.getElementById(`step${currentStep}`).classList.remove('active');
    document.getElementById(`step${currentStep + 1}`).classList.add('active');
}

function prevStep(currentStep) {
    document.getElementById(`step${currentStep}`).classList.remove('active');
    document.getElementById(`step${currentStep - 1}`).classList.add('active');
}

// اختيار نوع التصميم
const designTypes = document.querySelectorAll('.design-type');
designTypes.forEach(type => {
    type.addEventListener('click', () => {
        designTypes.forEach(t => t.classList.remove('selected'));
        type.classList.add('selected');
    });
});

// إدارة تحميل الملفات
function handleFileSelect(files) {
    const fileList = document.getElementById('fileList');
    fileList.innerHTML = '';
    
    if (files.length > 0) {
        const list = document.createElement('ul');
        list.style.paddingRight = '20px';
        list.style.marginTop = '10px';
        
        for (let i = 0; i < files.length; i++) {
            const listItem = document.createElement('li');
            listItem.textContent = files[i].name;
            listItem.style.marginBottom = '5px';
            list.appendChild(listItem);
        }
        fileList.appendChild(list);
    }
}

// إظهار رسالة نجاح بعد إرسال النموذج
document.getElementById('orderForm').addEventListener('submit', function(e) {
    // لا نمنع الإرسال الافتراضي لأننا نريد إرسال البيانات إلى process-order.php
    // يمكن إضافة التحقق الإضافي هنا إذا لزم الأمر
});

// التنقل السلس للروابط
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});
