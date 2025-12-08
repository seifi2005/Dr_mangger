<?php

namespace App\Services;

class ValidationService
{
    public function validateDoctor(array $data, ?int $excludeId = null): array
    {
        $errors = [];

        if (empty($data['first_name'])) {
            $errors['firstName'] = 'نام الزامی است';
        }

        if (empty($data['last_name'])) {
            $errors['lastName'] = 'نام خانوادگی الزامی است';
        }

        if (empty($data['national_code'])) {
            $errors['nationalCode'] = 'کد ملی الزامی است';
        } elseif (strlen($data['national_code']) !== 10 || !ctype_digit($data['national_code'])) {
            $errors['nationalCode'] = 'کد ملی باید 10 رقم باشد';
        } else {
            // Check for duplicate national code
            $doctorModel = new \App\Models\Doctor();
            $existing = $doctorModel->findByNationalCode($data['national_code'], $excludeId);
            if ($existing) {
                $errors['nationalCode'] = 'این کد ملی قبلاً ثبت شده است';
            }
        }

        if (empty($data['medical_license'])) {
            $errors['medicalLicense'] = 'شماره نظام پزشکی الزامی است';
        }

        if (!empty($data['mobile']) && (strlen($data['mobile']) !== 11 || !preg_match('/^09/', $data['mobile']))) {
            $errors['mobile'] = 'شماره موبایل باید 11 رقم و با 09 شروع شود';
        }

        return $errors;
    }

    public function validatePharmacy(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'نام داروخانه الزامی است';
        }

        if (empty($data['license_number'])) {
            $errors['licenseNumber'] = 'شماره پروانه الزامی است';
        }

        if (empty($data['owner_name'])) {
            $errors['ownerName'] = 'نام صاحب داروخانه الزامی است';
        }

        if (empty($data['owner_national_code'])) {
            $errors['ownerNationalCode'] = 'کد ملی صاحب الزامی است';
        } elseif (strlen($data['owner_national_code']) !== 10 || !ctype_digit($data['owner_national_code'])) {
            $errors['ownerNationalCode'] = 'کد ملی باید 10 رقم باشد';
        }

        if (empty($data['address'])) {
            $errors['address'] = 'آدرس الزامی است';
        }

        return $errors;
    }

    public function validateSpecialty(array $data): array
    {
        $errors = [];

        if (empty($data['name_fa'])) {
            $errors['nameFa'] = 'نام رشته (فارسی) الزامی است';
        }

        return $errors;
    }

    public function validateUser(array $data, ?int $excludeId = null): array
    {
        $errors = [];

        if (empty($data['first_name'])) {
            $errors['firstName'] = 'نام الزامی است';
        }

        if (empty($data['last_name'])) {
            $errors['lastName'] = 'نام خانوادگی الزامی است';
        }

        if (empty($data['national_code'])) {
            $errors['nationalCode'] = 'کد ملی الزامی است';
        } elseif (strlen($data['national_code']) !== 10 || !ctype_digit($data['national_code'])) {
            $errors['nationalCode'] = 'کد ملی باید 10 رقم باشد';
        } else {
            // Check for duplicate national code
            $userModel = new \App\Models\User();
            $existing = $userModel->findByNationalCode($data['national_code'], $excludeId);
            if ($existing) {
                $errors['nationalCode'] = 'این کد ملی قبلاً ثبت شده است';
            }
        }

        if (empty($data['mobile'])) {
            $errors['mobile'] = 'شماره موبایل الزامی است';
        } elseif (strlen($data['mobile']) !== 11 || !preg_match('/^09/', $data['mobile'])) {
            $errors['mobile'] = 'شماره موبایل باید 11 رقم و با 09 شروع شود';
        } else {
            // Check for duplicate mobile
            $userModel = new \App\Models\User();
            $existing = $userModel->findByMobile($data['mobile'], $excludeId);
            if ($existing) {
                $errors['mobile'] = 'این شماره موبایل قبلاً ثبت شده است';
            }
        }

        if (empty($data['role'])) {
            $errors['role'] = 'نوع دسترسی الزامی است';
        }

        return $errors;
    }
}

