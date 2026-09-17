import { TextLoop } from '@/components/ui/text-loop';

export default function ReactTest({ message }) {
    return (
        <div className="min-h-screen bg-background flex items-center justify-center">
            <div className="bg-card rounded-xl shadow-lg p-10 text-center space-y-4">
                <h1 className="text-3xl font-bold text-foreground">cult-ui en Kamo</h1>
                <div className="text-xl text-muted-foreground">
                    <TextLoop>
                        <span>Diseño moderno</span>
                        <span>Componentes React</span>
                        <span>Inertia.js</span>
                        <span>{message}</span>
                    </TextLoop>
                </div>
            </div>
        </div>
    );
}
