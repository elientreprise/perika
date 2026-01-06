import TimesheetValidateContainer from "./TimesheetValidateContainer.tsx";

type Props = {
    timesheetUuid: string;
    isValidating: boolean;
    setIsValidating: (value: boolean) => void;
};
export default function TimesheetValidate(props: Readonly<Props>) {


    return (
       <TimesheetValidateContainer {...props} />
    );
}
