import Step1PeriodContainer from "./Step1PeriodContainer.tsx";

type Props = {
    onNext: () => void;
    current: boolean;
};

export default function Step1Period(props: Readonly<Props>) {
    return <Step1PeriodContainer {...props} />;
}