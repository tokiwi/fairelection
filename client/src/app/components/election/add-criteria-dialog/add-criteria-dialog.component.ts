import { Component, Inject, OnInit } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { pictogramEnum } from '../../../core/enum/pictogram.enum';
import { FormUtil } from '../../../core/utils/form.util';
import { CriteriaService } from '../../../core/services/criteria.service';
import { NotificationService } from '../../../core/services/notification.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { Criteria } from '../../../core/models/criteria.model';
import { Election } from '../../../core/models/election.model';
import { finalize } from 'rxjs/operators';

@Component({
    selector: 'app-add-criteria-dialog',
    templateUrl: './add-criteria-dialog.component.html',
    styleUrls: ['./add-criteria-dialog.component.scss']
})
export class AddCriteriaDialogComponent implements OnInit {
    public loading = false;
    public submitted = false;
    public pictograms = pictogramEnum;

    public form = this.fb.group({
        '@id': [null],
        election: [null],
        name: [null, [Validators.required, Validators.maxLength(255)]],
        pictogram: [null, [Validators.required]],
        items: this.fb.array([
            this.createItemGroup()
        ]),
    });

    constructor(
        private fb: FormBuilder,
        private criteriaService: CriteriaService,
        private notificationService: NotificationService,
        public dialogRef: MatDialogRef<AddCriteriaDialogComponent>,
        @Inject(MAT_DIALOG_DATA) public data: {election: Election; criteria: Criteria}
    ) {
        this.form.patchValue({election: data.election['@id']});

        if (data && data.criteria) {
            const criteria = data.criteria;

            const itemsArray = this.form.get('items') as FormArray;
            itemsArray.clear();
            criteria.items.forEach(() => this.addItem());

            this.form.patchValue(criteria);
        }
    }

    ngOnInit(): void {
    }

    public save(): void {
        this.submitted = true;

        if (this.form.valid) {
            this.loading = true;

            if (null === this.form.get('@id').value) {
                this.criteriaService.create(this.form.value).pipe(
                    finalize(() => this.loading = false)
                ).subscribe(() => {
                    this.notificationService.success('toast.criteria_created');

                    this.dialogRef.close();
                });
            } else {
                this.criteriaService.update(this.form.get('@id').value, this.form.value).pipe(
                    finalize(() => this.loading = false)
                ).subscribe(() => {
                    this.notificationService.success('toast.criteria_edited');

                    this.dialogRef.close();
                });
            }
        } else {
            FormUtil.validateAllFormFields(this.form);
        }
    }

    public createItemGroup(): FormGroup {
        return this.fb.group({
            '@id': [null, []],
            name: [null, [Validators.required, Validators.maxLength(255)]],
            acronym: [null, [Validators.required, Validators.maxLength(5)]],
        });
    }

    public removeItemGroup(i: number): void {
        const items = this.form.get('items') as FormArray;

        if (items.length > 1) {
            items.removeAt(i);
        } else {
            items.reset();
        }
    }

    addItem(): void {
        const itemsArray = this.form.get('items') as FormArray;
        itemsArray.push(this.createItemGroup());
    }
}
