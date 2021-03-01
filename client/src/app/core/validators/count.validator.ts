import { FormArray } from '@angular/forms';

export function CountValidator(min: number): any {
    return (c: FormArray): {[key: string]: any} => {
        if (c.length >= min) {
            return null;
        }

        return { count: {valid: false, min }};
    };
}
