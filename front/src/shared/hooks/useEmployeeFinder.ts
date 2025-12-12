import {useState, useCallback} from "react";
import {API_URL} from "../../app/config/api.tsx";


export function useEmployeeFinder() {
    const [employeeIri, setEmployeeIri] = useState<string | undefined>();
    const [loading, setLoading] = useState<boolean>(false);
    const [error, setError] = useState<string | null>(null);

    const buildEmployeeIri = useCallback(async (uuidEmployee: string) => {

        setError(null);
        setLoading(true);

        try {
            setEmployeeIri(API_URL+'/employees/'+uuidEmployee)
        } catch (err: any) {
            console.error(err);
            setError(err.message ?? "Erreur inconnue");
        } finally {
            setLoading(false);
        }
    }, []);


    return {
        employeeIri,
        buildEmployeeIri,
        loading,
        error,
        setError
    };
}
