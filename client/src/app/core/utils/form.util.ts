import { Injectable } from '@angular/core';
import { FormArray, FormControl, FormGroup } from '@angular/forms';

@Injectable()
export class FormUtil {
    /**
     * Validate all form fields of a form group
     *
     * <code>
     * this.formGroup = this.fb.group({
     *     foo: new FormControl('', [Validators.required]),
     *     bar: new FormControl('', [Validators.required]),
     * });
     *
     * this.validateAllFormFields(this.formGroup);
     * </code>
     */
    public static validateAllFormFields(formGroup: FormGroup): void {
        Object.keys(formGroup.controls).forEach((field) => {
            const control = formGroup.get(field);
            if (control instanceof FormControl) {
                control.markAsTouched({ onlySelf: true });
            } else if (control instanceof FormGroup) {
                FormUtil.validateAllFormFields(control);
            } else if (control instanceof FormArray) {
                Object.keys(control.controls).forEach(key => {
                    FormUtil.validateAllFormFields(control.controls[key]);
                });
            }
        });
    }
}
