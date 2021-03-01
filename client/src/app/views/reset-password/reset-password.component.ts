import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { finalize } from 'rxjs/operators';
import { FormUtil } from '../../core/utils/form.util';
import { ActivatedRoute, Router } from '@angular/router';
import { UserService } from '../../core/services/user.service';
import { NotificationService } from '../../core/services/notification.service';

@Component({
    selector: 'app-reset-password',
    templateUrl: './reset-password.component.html',
    styleUrls: ['./reset-password.component.scss']
})
export class ResetPasswordComponent implements OnInit {
    public loading = false;
    public hidePassword = true;

    public form = this.fb.group({
        password: [null, [Validators.required, Validators.minLength(8), Validators.maxLength(4096)]],
    });

    private token: string;

    constructor(
        private fb: FormBuilder,
        private route: ActivatedRoute,
        private router: Router,
        private userService: UserService,
        private notification: NotificationService
    ) {
        this.token = this.route.snapshot.paramMap.get('token');
    }

    ngOnInit(): void {
    }

    public save(): void {
        if (this.form.valid) {
            this.loading = true;

            this.userService.resetPassword(this.form.value, this.token).pipe(
                finalize(() => this.loading = false)
            ).subscribe(() => {
                this.notification.success('toast.reset_password_success');

                void this.router.navigateByUrl('/login');
            });
        } else {
            FormUtil.validateAllFormFields(this.form);
        }
    }
}
