import { useState, useEffect } from "react";
import type {UserType} from "../types/UserType.ts";
import {getEmployeeByUuid} from "../services/employee.ts";
import {UserSchema} from "../types/UserType.ts";

export function useEmployee(uuid: string) {

    const [employee, setEmployee] = useState<UserType>();
    const [notFound, setNotFound] = useState<boolean>(false);

    useEffect(() => {

        async function load() {
            try {
                const response = await getEmployeeByUuid(uuid);
                if (response) {
                    const parsed = UserSchema.parse(response)
                    setEmployee(parsed)
                }

            } catch (err: any) {
                setNotFound(err.status == 404)
                console.log(err)
            }
        }

        load();

    }, [uuid]);

    return { employee, notFound };
}
