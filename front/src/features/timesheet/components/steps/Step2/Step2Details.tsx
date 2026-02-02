import Step2DetailsContainer from "./Step2DetailsContainer.tsx";


type Props = {
    onNext: () => void;
    onPrevious: () => void;
    current: boolean
};

export default function Step2Details(props: Readonly<Props>) {

    return <Step2DetailsContainer {...props} />;

}
