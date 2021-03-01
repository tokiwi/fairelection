import { Component } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { UserService } from '../../core/services/user.service';
import { NotificationService } from '../../core/services/notification.service';
import { FormUtil } from '../../core/utils/form.util';
import { finalize, take } from 'rxjs/operators';

@Component({
    selector: 'app-reset-password-request',
    templateUrl: './reset-password-request.component.html',
    styleUrls: ['./reset-password-request.component.scss']
})
export class ResetPasswordRequestComponent {
    public loading = false;

    public form = this.fb.group({
        email: [null, [Validators.required, Validators.email, Validators.maxLength(255)]],
    });

    constructor(
        private fb: FormBuilder,
        private userService: UserService,
        private notification: NotificationService,
    ) { }

    public send(): void {
        if (this.form.valid) {
            this.loading = true;

            this.userService.resetPasswordRequest(this.form.value).pipe(
                finalize(() => this.loading = false),
                take(1)
            ).subscribe(() => {
                this.notification.success('toast.reset_password_request_success');
            });
        } else {
            FormUtil.validateAllFormFields(this.form);
        }
    }
}
