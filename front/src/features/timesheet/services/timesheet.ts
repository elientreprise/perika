
import type {CalculatePeriodPayload} from "../types/CalculatePeriodPayload.ts";
import type {CalculatePeriodResponse} from "../types/CalculatePeriodResponse.ts";
import type {CheckExistsResponse} from "../types/CheckExistsResponse.ts";
import type {CheckExistsPayload} from "../types/CheckExistsPayload.ts";
import type {TimesheetType} from "../types/TimesheetType.ts";
import type {TimesheetCreateResponse} from "../types/TimesheetCreateResponse.ts";
import {get, post} from "../../../app/services/api.ts";
import type {CommentPayloadType, TimesheetPayloadType} from "../types/TimesheetPayload.ts";
import {API_URL} from "../../../app/config/api.tsx";
import type {TimesheetSearchParameters} from "../types/TimesheetSearchParameters.ts";
import {CommentPayloadSchema} from "../types/TimesheetPayload.ts";
import type {CommentCreateResponse} from "../types/CommentCreateResponse.ts";


export async function calculatePeriod(data: CalculatePeriodPayload ): Promise<CalculatePeriodResponse> {
    return post<CalculatePeriodResponse>("/timesheets/calculate-period", data);
}

export async function timesheetCheckExist(data: CheckExistsPayload ): Promise<CheckExistsResponse> {
    return post<CheckExistsResponse>("/timesheets/check-exists", data);
}
export async function create(data: TimesheetPayloadType ): Promise<TimesheetCreateResponse> {
    return post<TimesheetCreateResponse>("/timesheets", data);
}

export async function createComment(data: CommentPayloadType ): Promise<CommentCreateResponse> {
    return post<CommentCreateResponse>("/timesheet-comments", data);
}

export async function getTimesheetByUuid(uuid: string ): Promise<TimesheetType> {
    return get<TimesheetType>(`/timesheets/${uuid}`);
}

export async function getTimesheetByEmployee(employeeUuid: string, timesheetUuid: string ): Promise<TimesheetType> {
    return get<TimesheetType>(`/employees/${employeeUuid}/timesheets/${timesheetUuid}`);
}



export async function searchTimesheets(employeeUuid, parameters: TimesheetSearchParameters = {}): Promise<TimesheetType[]> {


    const url = employeeUuid ? new URL(`${API_URL}/employees/${employeeUuid}/timesheets`) : new URL(`${API_URL}/timesheets`);

    for (const [key, value] of Object.entries(parameters)) {
        if (value !== undefined && value !== null && value !== "" && key !== 'employee') {
            url.searchParams.append(key, String(value));
        }
    }

    const response = await get(url.toString());

    return response.member || response.data;
}