
import type {CalculatePeriodPayload} from "../types/CalculatePeriodPayload.ts";
import type {CalculatePeriodResponse} from "../types/CalculatePeriodResponse.ts";
import type {CheckExistsResponse} from "../types/CheckExistsResponse.ts";
import type {CheckExistsPayload} from "../types/CheckExistsPayload.ts";
import type {TimesheetType} from "../types/TimesheetType.ts";
import type {TimesheetCreateResponse} from "../types/TimesheetCreateResponse.ts";
import {get, post} from "../../../app/services/api.ts";
import type {TimesheetPayloadType} from "../types/TimesheetPayload.ts";


export async function calculatePeriod(data: CalculatePeriodPayload ): Promise<CalculatePeriodResponse> {
    return post<CalculatePeriodResponse>("/timesheets/calculate-period", data);
}

export async function timesheetCheckExist(data: CheckExistsPayload ): Promise<CheckExistsResponse> {
    return post<CheckExistsResponse>("/timesheets/check-exists", data);
}
export async function create(data: TimesheetPayloadType ): Promise<TimesheetCreateResponse> {
    return post<TimesheetCreateResponse>("/timesheets", data);
}

export async function getTimesheetByUuid(uuid: string ): Promise<TimesheetType> {
    return get<TimesheetType>(`/timesheets/${uuid}`);
}

export async function getTimesheetByEmployee(employeeUuid: string, timesheetUuid: string ): Promise<TimesheetType> {
    return get<TimesheetType>(`/employees/${employeeUuid}/timesheets/${timesheetUuid}`);
}