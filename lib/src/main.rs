use chrono::{NaiveDate, Weekday};
use clap::{Parser, Subcommand};
use inquire::{Confirm, DateSelect, Editor, MultiSelect, Password, Select, Text};
use std::error::Error;
use std::ffi::OsStr;
use std::process;

#[derive(Parser)]
#[command(author, version, about, long_about = None)]
struct Cli {
    #[command(subcommand)]
    command: Option<Commands>,
}

#[derive(Subcommand)]
enum Commands {
    Text {
        #[arg(short, long)]
        prompt: Option<String>,
        #[arg(short, long)]
        initial: Option<String>,
        #[arg(short, long)]
        placeholder: Option<String>,
        #[arg(short, long)]
        hint: Option<String>,
    },
    Editor {
        #[arg(short, long)]
        prompt: Option<String>,
        #[arg(short, long)]
        initial: Option<String>,
        #[arg(short, long)]
        program: Option<String>,
        #[arg(short, long)]
        hint: Option<String>,
    },
    Password {
        #[arg(short, long)]
        prompt: Option<String>,
        #[arg(long)]
        confirm: bool,
        #[arg(short, long)]
        hint: Option<String>,
    },
    Confirm {
        #[arg(short, long)]
        prompt: Option<String>,
        #[arg(short, long)]
        hint: Option<String>,
        #[arg(short, long)]
        default_true: bool,
    },
    Select {
        #[arg(short, long)]
        prompt: Option<String>,
        #[arg(short, long, required = true)]
        options: Vec<String>,
        #[arg(short, long)]
        hint: Option<String>,
    },
    MultiSelect {
        #[arg(short, long)]
        prompt: Option<String>,
        #[arg(short, long, required = true)]
        options: Vec<String>,
        #[arg(short, long)]
        initial: Vec<String>,
        #[arg(short, long)]
        hint: Option<String>,
    },
    Date {
        #[arg(short, long)]
        prompt: Option<String>,
        #[arg(long)]
        min_date: Option<String>,
        #[arg(long)]
        max_date: Option<String>,
        #[arg(short, long)]
        hint: Option<String>,
    },
}

fn main() {
    let result = run();
    match result {
        Ok(_) => {}
        Err(e) => {
            let error_message = e.to_string().to_lowercase();
            if error_message.contains("interrupted") {
                eprintln!("");
                process::exit(1);
            } else {
                eprintln!("Error: {}", e);
                process::exit(1);
            }
        }
    }
}

fn run() -> Result<(), Box<dyn Error>> {
    let cli = Cli::parse();

    match &cli.command {
        Some(Commands::Text { prompt, initial, placeholder, hint }) => {
            let mut text = Text::new(prompt.as_deref().unwrap_or("Enter text:"));
            if let Some(initial) = initial {
                text = text.with_initial_value(initial);
            }
            if let Some(placeholder) = placeholder {
                text = text.with_placeholder(placeholder);
            }
            if let Some(hint) = hint {
                text = text.with_help_message(hint);
            }
            let answer = text.prompt()?;
            println!("{}", answer);
        }
        Some(Commands::Editor { prompt, initial, program, hint }) => {
            let mut editor = Editor::new(prompt.as_deref().unwrap_or("Edit text:"));
            if let Some(initial) = initial {
                editor = editor.with_predefined_text(initial);
            }
            if let Some(program) = program {
                editor = editor.with_editor_command(OsStr::new(program));
            }
            if let Some(hint) = hint {
                editor = editor.with_help_message(hint);
            }
            let answer = editor.prompt()?;
            println!("{}", answer);
        }
        Some(Commands::Password { prompt, confirm, hint }) => {
            let mut password = Password::new(prompt.as_deref().unwrap_or("Enter password:"))
                .with_display_mode(inquire::PasswordDisplayMode::Masked);

            if !*confirm {
                password = password.without_confirmation();
            }

            if let Some(hint) = hint {
                password = password.with_help_message(hint);
            }

            let answer = password.prompt()?;
            println!("{}", answer);
        }
        Some(Commands::Confirm { prompt, hint, default_true }) => {
            let mut confirm = Confirm::new(prompt.as_deref().unwrap_or("Confirm?")).with_default(*default_true);
            if let Some(hint) = hint {
                confirm = confirm.with_help_message(hint);
            }
            let answer = confirm.prompt()?;
            println!("{}", answer);
            if !answer {
                process::exit(1);
            }
        }
        Some(Commands::Select { prompt, options, hint }) => {
            let mut select = Select::new(prompt.as_deref().unwrap_or("Select an option:"), options.clone());
            if let Some(hint) = hint {
                select = select.with_help_message(hint);
            }
            let answer = select.prompt()?;
            println!("{}", answer);
        }
        Some(Commands::MultiSelect { prompt, options, initial, hint }) => {
            let defaults: Vec<usize> = initial.iter().map(|item| options.iter().position(|option| option == item).unwrap()).collect();
            let mut multi_select = MultiSelect::new(prompt.as_deref().unwrap_or("Select options:"), options.clone()).with_default(defaults.as_slice());
            if let Some(hint) = hint {
                multi_select = multi_select.with_help_message(hint);
            }
            let answers = multi_select.prompt()?;
            println!("{}", answers.join("\n"));
        }
        Some(Commands::Date {
                 prompt,
                 min_date,
                 max_date,
                 hint
             }) => {
            let mut date_select = DateSelect::new(prompt.as_deref().unwrap_or("Select a date:"))
                .with_week_start(Weekday::Mon);

            if let Some(min) = min_date {
                let min_date_parsed = NaiveDate::parse_from_str(min, "%Y-%m-%d")?;
                date_select = date_select.with_min_date(min_date_parsed);
                date_select = date_select.with_starting_date(min_date_parsed);
            }
            if let Some(max) = max_date {
                let max_date_parsed = NaiveDate::parse_from_str(max, "%Y-%m-%d")?;
                date_select = date_select.with_max_date(max_date_parsed);
                date_select = date_select.with_starting_date(max_date_parsed);
            }
            if let Some(hint) = hint {
                date_select = date_select.with_help_message(hint);
            }

            let answer = date_select.prompt()?;
            println!("{}", answer);
        }
        None => {
            let _ = Cli::parse_from(&["", "--help"]);
        }
    }

    Ok(())
}
