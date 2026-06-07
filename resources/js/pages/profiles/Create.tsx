import AppLayout from "@/layouts/app/AppLayout";
import { useForm } from "@inertiajs/react";

export default function Create() {
    const {
        data,
        setData,
        post,
        processing,
        errors,
    } = useForm({
        username: "",
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();

        post("/profiles", {
            preserveScroll: true,

            onSuccess: () => {
                console.log("Profile created");
            },

            onError: (errors) => {
                console.log(errors);
            },
        });
    };

    return (
        <AppLayout>
            <div className="max-w-2xl mx-auto">

                <h1 className="text-3xl font-bold mb-6">
                    Add Instagram Profile
                </h1>

                <form
                    onSubmit={submit}
                    className="bg-white rounded-lg shadow p-6"
                >

                    <div className="mb-4">

                        <label className="block mb-2 font-medium">
                            Username
                        </label>

                        <input
                            type="text"
                            value={data.username}
                            onChange={(e) =>
                                setData(
                                    "username",
                                    e.target.value
                                )
                            }
                            placeholder="cristiano"
                            className="w-full border rounded p-3"
                        />

                        {errors.username && (
                            <p className="text-red-500 mt-2">
                                {errors.username}
                            </p>
                        )}

                    </div>

                    <button
                        type="submit"
                        disabled={processing}
                        className="bg-black text-white px-5 py-3 rounded"
                    >
                        {processing
                            ? "Saving..."
                            : "Save Profile"}
                    </button>

                </form>

            </div>
        </AppLayout>
    );
}