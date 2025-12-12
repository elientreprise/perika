
import {get} from "../../../app/services/api.ts";
import type {UserType} from "../types/UserType.ts";


export async function getEmployeeIriByCode(employeeCode: number ): Promise<any> {
    return get("/employees/" + employeeCode);
}

export async function getEmployeeByUuid(uuid: string): Promise<UserType> {
    return get("employees/" + uuid);
}

export async function getEmployeeByIri(iri: string): Promise<UserType> {
    return get(iri);
}