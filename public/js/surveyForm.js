function surveyForm() {
    return {
        survey: {
            title: "",
            description: "",
            start_date: "",
            end_date: "",
            is_anonymous: false,
        },
        questions: [],

        addQuestion() {
            this.questions.push({
                title: "",
                question_type: "text",
                options: [],
            });
        },

        removeQuestion(index) {
            this.questions.splice(index, 1);
        },

        updateQuestionOptions(qIndex) {
            const question = this.questions[qIndex];
            if (
                question.question_type === "single_choice" ||
                question.question_type === "multiple_choice"
            ) {
                if (question.options.length === 0) {
                    question.options = [""];
                }
            } else {
                question.options = [];
            }
        },

        addOption(qIndex) {
            this.questions[qIndex].options.push("");
        },

        removeOption(qIndex, oIndex) {
            this.questions[qIndex].options.splice(oIndex, 1);
        },

        submitSurvey() {
            // Clean up questions by removing empty options
            const cleanedQuestions = this.questions.map((q) => ({
                title: q.title,
                question_type: q.question_type,
                options: q.options
                    ? q.options.filter((opt) => opt.trim() !== "")
                    : [],
            }));

            const formData = {
                ...this.survey,
                questions: cleanedQuestions,
                _token:
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content ||
                    document.querySelector('input[name="_token"]')?.value,
            };

            console.log("Submitting survey with data:", formData);
            console.log("Number of questions:", cleanedQuestions.length);

            const storeUrl = window.appConfig.urls.store;
            console.log("Store URL:", storeUrl);

            fetch(storeUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": formData._token,
                },
                body: JSON.stringify(formData),
            })
                .then((response) => response.json())
                .then((data) => {
                    console.log("Server response:", data);
                    if (data.success || data.message) {
                        window.location.href = window.appConfig.urls.index;
                    } else {
                        alert("Error creating survey");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("An error occurred while creating the survey");
                });
        },
    };
}
