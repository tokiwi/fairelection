import { Component } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { FormUtil } from '../../core/utils/form.util';
import { UserService } from '../../core/services/user.service';
import { NotificationService } from '../../core/services/notification.service';
import { AuthService } from '../../core/services/auth.service';
import { MatDialogRef } from '@angular/material/dialog';
import { finalize, mergeMap } from 'rxjs/operators';

@Component({
    selector: 'app-register-dialog',
    templateUrl: './register-dialog.component.html',
    styleUrls: ['./register-dialog.component.scss']
})
export class RegisterDialogComponent {
    public loading = false;
    public hidePassword = true;

    public form = this.fb.group({
        fullName: [null, [Validators.required, Validators.maxLength(255)]],
        email: [null, [Validators.required, Validators.email, Validators.maxLength(255)]],
        password: [null, [Validators.required, Validators.minLength(8), Validators.maxLength(4096)]],
    });

    constructor(
        private userService: UserService,
        private fb: FormBuilder,
        private notificationService: NotificationService,
        private authService: AuthService,
        public dialogRef: MatDialogRef<RegisterDialogComponent>,
    ) { }

    public register(): void {
        if (this.form.valid) {
            this.loading = true;

            this.userService.register(this.form.value).pipe(
                finalize(() => this.loading = false)
            ).pipe(
                mergeMap(() => this.authService.login(this.form.value))
            ).subscribe(() => {
                this.notificationService.success('toast.welcome_home');
                this.dialogRef.close(true);
            });
        } else {
            FormUtil.validateAllFormFields(this.form);
        }
    }
}
