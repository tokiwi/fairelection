import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { AuthService } from '../../core/services/auth.service';
import { FormUtil } from '../../core/utils/form.util';
import { NotificationService } from '../../core/services/notification.service';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { RegisterDialogComponent } from '../register-dialog/register-dialog.component';
import { finalize } from 'rxjs/operators';

@Component({
    selector: 'app-login-dialog',
    templateUrl: './login-dialog.component.html',
    styleUrls: ['./login-dialog.component.scss']
})
export class LoginDialogComponent implements OnInit {
    public loading = false;
    public hidePassword = true;

    public form = this.fb.group({
        email: [null, [Validators.required, Validators.email, Validators.maxLength(255)]],
        password: [null, [Validators.required, Validators.maxLength(4096)]],
    });

    constructor(
        private fb: FormBuilder,
        private authService: AuthService,
        private notificationService: NotificationService,
        private dialog: MatDialog,
        public dialogRef: MatDialogRef<LoginDialogComponent>,
    ) { }

    ngOnInit(): void {
        this.dialogRef.backdropClick().subscribe(() => {
            this.dialogRef.close(false);
        });
    }

    public login(): void {
        if (this.form.valid) {
            this.loading = true;

            this.authService.login(this.form.value).pipe(
                finalize(() => this.loading = false)
            ).subscribe(() => {
                this.notificationService.success('toast.welcome_home');
                this.dialogRef.close(true);
            }, () => {
                this.notificationService.error('toast.wrong_username_or_password');
            });
        } else {
            FormUtil.validateAllFormFields(this.form);
        }
    }


    public register(): void {
        this.dialogRef.close();

        this.dialog.open(RegisterDialogComponent, {
            minWidth: 400
        });
    }
}
