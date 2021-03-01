import { Injectable } from '@angular/core';
import { HttpParams } from '@angular/common/http';

@Injectable()
export class HttpParamUtil {
    private static paramsMap: Map<string, string>;

    public static create(): HttpParamUtil {
        this.paramsMap = new Map<string, string>();

        return new HttpParamUtil();
    }

    public add(key: string, value: string): this {
        HttpParamUtil.paramsMap.set(key, value);

        return this;
    }

    public getHttpParams(): HttpParams {
        let httpParams = new HttpParams();
        HttpParamUtil.paramsMap.forEach((value: any, key: any) => {
            httpParams = httpParams.set(key, value);
        });

        return httpParams;
    }
}
