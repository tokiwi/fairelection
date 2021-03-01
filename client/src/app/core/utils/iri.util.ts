import { Injectable } from '@angular/core';

@Injectable()
export class IriUtil {

    public static extractId(iri: string): string {
        const parts = iri.split('/');

        return parts.pop();
    }
}
